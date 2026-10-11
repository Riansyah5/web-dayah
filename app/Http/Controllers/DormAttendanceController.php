<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Dorm;
use App\Models\DormAttendance;
use App\Models\DormAttendanceDetail;
use App\Models\Permission;
use App\Models\Room;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DormAttendanceController extends Controller
{
    /**
     * Menampilkan daftar riwayat sesi absensi asrama.
     */
    public function index(Request $request)
    {
        $dorms = Dorm::orderBy('name')->get();
        $rooms = Room::with('dorm')->orderBy('name')->get();

        $query = DormAttendance::with(['dorm', 'room.warden', 'recorder'])
            ->withCount([
                'details as total_students',
                'details as present_count' => fn($q) => $q->where('status', 'present'),
                'details as sick_count' => fn($q) => $q->where('status', 'sick'),
                'details as permit_count' => fn($q) => $q->where('status', 'permission'),
                'details as alpha_count' => fn($q) => $q->where('status', 'alpha'),
            ]);

        // Filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filter asrama
        if ($request->filled('dorm_id')) {
            $query->where('dorm_id', $request->dorm_id);
        }

        // Filter kamar
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        // Filter sesi
        if ($request->filled('session')) {
            $query->where('session', $request->session);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Ringkasan Hari Ini
        $today = now()->toDateString();
        $todaySummary = DormAttendanceDetail::whereHas('dormAttendance', function ($q) use ($today) {
            $q->whereDate('date', $today);
        })->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick,
            SUM(CASE WHEN status = 'permission' THEN 1 ELSE 0 END) as permission,
            SUM(CASE WHEN status = 'alpha' THEN 1 ELSE 0 END) as alpha
        ")->first();

        return view('dorm_attendances.index', compact('attendances', 'dorms', 'rooms', 'todaySummary'));
    }

    /**
     * Form pemilihan asrama/kamar dan absensi santri.
     */
    public function create(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Belum ada Tahun Ajaran aktif! Aktifkan tahun ajaran terlebih dahulu.');
        }

        $dorms = Dorm::with('rooms')->orderBy('name')->get();
        $rooms = collect();

        $selectedDormId = $request->dorm_id;
        $selectedRoomId = $request->room_id;
        $date = $request->date ?? now()->toDateString();
        $session = $request->session ?? 'malam';

        if ($selectedDormId) {
            $rooms = Room::where('dorm_id', $selectedDormId)->orderBy('name')->get();
        }

        $students = collect();
        $activePermissions = collect();
        $existingAttendance = null;

        if ($selectedDormId) {
            // Cek apakah sudah pernah diabsen untuk kombinasi ini
            $existingAttendance = DormAttendance::where('dorm_id', $selectedDormId)
                ->when($selectedRoomId, fn($q) => $q->where('room_id', $selectedRoomId), fn($q) => $q->whereNull('room_id'))
                ->whereDate('date', $date)
                ->where('session', $session)
                ->first();

            // Ambil santri aktif yang ditempatkan di asrama / kamar ini pada tahun ajaran aktif
            $studentsQuery = Student::where('status', 'active')
                ->whereHas('roomAssignments', function ($q) use ($activeYear, $selectedDormId, $selectedRoomId) {
                    $q->where('academic_year_id', $activeYear->id);
                    if ($selectedRoomId) {
                        $q->where('room_id', $selectedRoomId);
                    } else {
                        $q->whereHas('room', fn($r) => $r->where('dorm_id', $selectedDormId));
                    }
                })
                ->with(['roomAssignments' => function ($q) use ($activeYear) {
                    $q->where('academic_year_id', $activeYear->id)->with('room.dorm');
                }])
                ->orderBy('class_group')
                ->orderBy('name');

            $students = $studentsQuery->get();

            if ($students->isNotEmpty()) {
                // Deteksi otomatis santri yang memiliki izin keluar/pulang resmi pada tanggal tersebut
                $activePermissions = Permission::whereIn('student_id', $students->pluck('id'))
                    ->where('status', 'approved')
                    ->whereDate('start_date', '<=', $date)
                    ->where(function ($q) use ($date) {
                        $q->whereDate('end_date', '>=', $date)
                            ->orWhereNull('returned_at')
                            ->orWhereDate('returned_at', '>=', $date);
                    })
                    ->get()
                    ->keyBy('student_id');
            }
        }

        return view('dorm_attendances.create', compact(
            'activeYear',
            'dorms',
            'rooms',
            'selectedDormId',
            'selectedRoomId',
            'date',
            'session',
            'students',
            'activePermissions',
            'existingAttendance'
        ));
    }

    /**
     * Menyimpan sesi absensi asrama dan detail status santri.
     */
    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'dorm_id'          => 'required|exists:dorms,id',
            'room_id'          => 'nullable|exists:rooms,id',
            'date'             => 'required|date',
            'session'          => 'required|string|max:50',
            'attendance'       => 'required|array',
            'attendance.*'     => 'in:present,sick,permission,alpha',
            'student_room_ids' => 'required|array',
            'notes'            => 'nullable|string',
            'student_notes'    => 'nullable|array',
            'permission_ids'   => 'nullable|array',
        ]);

        // Cek duplikasi sebelum insert
        $exists = DormAttendance::where('dorm_id', $request->dorm_id)
            ->when($request->room_id, fn($q) => $q->where('room_id', $request->room_id), fn($q) => $q->whereNull('room_id'))
            ->whereDate('date', $request->date)
            ->where('session', $request->session)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Sesi absensi untuk waktu dan asrama/kamar ini sudah pernah dibuat sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // 1. Buat Header Sesi Absensi
            $attendance = DormAttendance::create([
                'academic_year_id' => $request->academic_year_id,
                'dorm_id'          => $request->dorm_id,
                'room_id'          => $request->room_id,
                'date'             => $request->date,
                'session'          => $request->session,
                'recorded_by'      => auth()->id(),
                'notes'            => $request->notes,
            ]);

            // 2. Simpan Detail Kehadiran Santri
            foreach ($request->attendance as $studentId => $status) {
                $permissionId = $request->permission_ids[$studentId] ?? null;

                DormAttendanceDetail::create([
                    'dorm_attendance_id' => $attendance->id,
                    'student_id'         => $studentId,
                    'room_id'            => $request->student_room_ids[$studentId] ?? $request->room_id,
                    'status'             => $status,
                    'permission_id'      => $status === 'permission' ? $permissionId : null,
                    'notes'              => $request->student_notes[$studentId] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('dorm-attendance.show', $attendance->id)
                ->with('success', 'Absensi pengasuhan asrama berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan absensi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail hasil absensi asrama.
     */
    public function show(DormAttendance $dormAttendance)
    {
        $dormAttendance->load([
            'dorm',
            'room.warden',
            'recorder',
            'details' => function ($q) {
                $q->with(['student', 'room', 'permission']);
            }
        ]);

        $summary = [
            'total'      => $dormAttendance->details->count(),
            'present'    => $dormAttendance->details->where('status', 'present')->count(),
            'sick'       => $dormAttendance->details->where('status', 'sick')->count(),
            'permission' => $dormAttendance->details->where('status', 'permission')->count(),
            'alpha'      => $dormAttendance->details->where('status', 'alpha')->count(),
        ];

        return view('dorm_attendances.show', compact('dormAttendance', 'summary'));
    }

    /**
     * Form edit absensi asrama.
     */
    public function edit(DormAttendance $dormAttendance)
    {
        $dormAttendance->load(['dorm', 'room', 'details.student', 'details.room', 'details.permission']);

        return view('dorm_attendances.edit', compact('dormAttendance'));
    }

    /**
     * Memperbarui data absensi asrama.
     */
    public function update(Request $request, DormAttendance $dormAttendance)
    {
        $request->validate([
            'notes'         => 'nullable|string',
            'attendance'    => 'required|array',
            'attendance.*'  => 'in:present,sick,permission,alpha',
            'student_notes' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $dormAttendance->update([
                'notes' => $request->notes,
            ]);

            foreach ($request->attendance as $detailId => $status) {
                $detail = DormAttendanceDetail::where('id', $detailId)
                    ->where('dorm_attendance_id', $dormAttendance->id)
                    ->first();

                if ($detail) {
                    $detail->update([
                        'status' => $status,
                        'notes'  => $request->student_notes[$detailId] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('dorm-attendance.show', $dormAttendance->id)
                ->with('success', 'Data absensi pengasuhan berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui absensi: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus sesi absensi asrama.
     */
    public function destroy(DormAttendance $dormAttendance)
    {
        try {
            $dormAttendance->delete();
            return redirect()->route('dorm-attendance.index')
                ->with('success', 'Sesi absensi pengasuhan berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal menghapus sesi absensi: ' . $e->getMessage());
        }
    }

    /**
     * Rekapitulasi absensi pengasuhan asrama.
     */
    public function recap(Request $request)
    {
        $dorms = Dorm::orderBy('name')->get();
        $rooms = Room::with('dorm')->orderBy('name')->get();

        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();
        $selectedDormId = $request->dorm_id;
        $selectedRoomId = $request->room_id;

        $query = DormAttendanceDetail::whereHas('dormAttendance', function ($q) use ($startDate, $endDate, $selectedDormId, $selectedRoomId) {
            $q->whereBetween('date', [$startDate, $endDate]);
            if ($selectedDormId) {
                $q->where('dorm_id', $selectedDormId);
            }
            if ($selectedRoomId) {
                $q->where('room_id', $selectedRoomId);
            }
        });

        // Rekap per santri
        $studentRecap = (clone $query)
            ->with(['student', 'room.dorm'])
            ->selectRaw("
                student_id,
                room_id,
                COUNT(*) as total_session,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick_count,
                SUM(CASE WHEN status = 'permission' THEN 1 ELSE 0 END) as permission_count,
                SUM(CASE WHEN status = 'alpha' THEN 1 ELSE 0 END) as alpha_count
            ")
            ->groupBy('student_id', 'room_id')
            ->orderByDesc('alpha_count')
            ->orderByDesc('sick_count')
            ->paginate(25)
            ->withQueryString();

        // Total akumulatif dalam rentang
        $overallStats = (clone $query)->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick,
            SUM(CASE WHEN status = 'permission' THEN 1 ELSE 0 END) as permission,
            SUM(CASE WHEN status = 'alpha' THEN 1 ELSE 0 END) as alpha
        ")->first();

        return view('dorm_attendances.recap', compact(
            'dorms',
            'rooms',
            'startDate',
            'endDate',
            'selectedDormId',
            'selectedRoomId',
            'studentRecap',
            'overallStats'
        ));
    }
}

