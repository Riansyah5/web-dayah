<?php

namespace App\Http\Controllers\Academic\Journal;

use App\Http\Controllers\Controller;
use App\Models\LessonSchedule;
use App\Models\TeachingJournal;
use App\Models\ScheduleSubstitute;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TeacherJournalController extends Controller
{
    // Dashboard Guru: Menampilkan Jadwal Hari Ini
    public function index()
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        if (!$teacher) abort(403, 'Akun ini tidak terhubung dengan data Guru.');

        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)

        // 1. Ambil Jadwal ASLI (Regular)
        // Kecuali jadwal yang hari ini sedang dibadalkan ke orang lain
        $regularSchedules = LessonSchedule::where('teacher_id', $teacher->id)
                            ->where('day_of_week', $dayOfWeek)
                            ->whereDoesntHave('substitutes', function($q) use ($today) {
                                $q->where('date', $today); // Cek apakah ada badal hari ini
                            })
                            ->with(['classroom', 'subject'])
                            ->orderBy('start_time')
                            ->get();

        // 2. Ambil Jadwal BADAL (Pengganti)
        // Jadwal orang lain yang didelegasikan ke guru ini hari ini
        $substituteSchedules = ScheduleSubstitute::where('substitute_teacher_id', $teacher->id)
                            ->where('date', $today)
                            ->with(['lessonSchedule.classroom', 'lessonSchedule.subject', 'lessonSchedule.teacher'])
                            ->get();

        return view('academic.journal.dashboard', compact('regularSchedules', 'substituteSchedules', 'today'));
    }

    // Form Isi Jurnal
    public function create($schedule_id)
    {
        $schedule = LessonSchedule::findOrFail($schedule_id);
        
        // Cek apakah jurnal sudah diisi hari ini?
        $existing = TeachingJournal::where('lesson_schedule_id', $schedule_id)
                    ->where('date', Carbon::today())
                    ->first();

        if ($existing) {
            // Jika sudah absen, redirect ke absensi siswa (Tahap 4)
            return redirect()->route('academic.journal.attendance', $existing->id);
        }

        return view('academic.journal.create', compact('schedule'));
    }

    // Proses Simpan (Clock In)
    public function store(Request $request, $schedule_id)
    {
        $request->validate([
            'topic' => 'required|string',
            'latitude' => 'required', // Wajib GPS
            'longitude' => 'required',
            'photo' => 'required|image|max:5120', // Wajib Foto (Max 5MB)
        ]);

        // 1. VALIDASI RADIUS GPS (Haversine Formula)
        // Koordinat Sekolah (Bisa diambil dari database setting)
        $schoolLat = -6.200000; // Ganti dengan koordinat sekolah Anda
        $schoolLng = 106.816666;
        $maxRadius = 100; // meter

        $distance = $this->calculateDistance($schoolLat, $schoolLng, $request->latitude, $request->longitude);

        if ($distance > $maxRadius) {
            // Uncomment baris ini untuk mengaktifkan blokir radius
            // return back()->with('error', 'Posisi Anda terlalu jauh dari sekolah (' . round($distance) . 'm). Silakan masuk area sekolah.');
        }

        // 2. Upload Foto
        $photoPath = $request->file('photo')->store('journal-proofs', 'public');

        // 3. Cek apakah ini Badal?
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        $teacherId = $teacher->id;
        $schedule = LessonSchedule::find($schedule_id);
        $isSubstitute = ($schedule->teacher_id != $teacherId);

        // 4. Simpan Jurnal
        $journal = TeachingJournal::create([
            'lesson_schedule_id' => $schedule_id,
            'teacher_id' => $teacherId,
            'date' => Carbon::today(),
            'clock_in_time' => now(),
            'topic' => $request->topic,
            'notes' => $request->notes,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'photo_proof' => $photoPath,
            'is_substitute' => $isSubstitute,
        ]);

        // Redirect ke Absensi Siswa
        return redirect()->route('academic.journal.attendance', $journal->id)
               ->with('success', 'Berhasil masuk kelas. Silakan absen siswa.');
    }

    // Helper Hitung Jarak (Meter)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}