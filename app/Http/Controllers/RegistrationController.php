<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Registration;
use App\Models\Stage;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    /**
     * ==============================================================
     * PANEL ADMIN (KELOLA PENDAFTAR PSB)
     * ==============================================================
     */
    public function index(Request $request)
    {
        $stages = Stage::all();
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();

        $query = Registration::with(['academicYear', 'student']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter status pembayaran
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter jenjang
        if ($request->filled('stage')) {
            $query->where('target_stage', $request->stage);
        }

        // Filter tahun ajaran
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        } elseif ($activeYear) {
            // Default filter ke tahun ajaran aktif jika ada
            $query->where('academic_year_id', $activeYear->id);
        }

        // Filter pencarian nama/no pendaftaran/NIK
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('registration_number', 'like', "%{$s}%")
                    ->orWhere('nik', 'like', "%{$s}%")
                    ->orWhere('nisn', 'like', "%{$s}%");
            });
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Ringkasan metrik PSB
        $yearId = $request->academic_year_id ?: ($activeYear->id ?? null);
        $statQuery = Registration::when($yearId, fn($q) => $q->where('academic_year_id', $yearId));

        $stats = [
            'total' => (clone $statQuery)->count(),
            'pending' => (clone $statQuery)->where('status', 'pending')->count(),
            'verified' => (clone $statQuery)->where('status', 'verified')->count(),
            'passed' => (clone $statQuery)->where('status', 'passed')->count(),
            'enrolled' => (clone $statQuery)->where('status', 'enrolled')->count(),
            'payment_unverified' => (clone $statQuery)->where('payment_status', 'unverified')->count(),
        ];

        return view('registrations.index', compact('registrations', 'stages', 'academicYears', 'activeYear', 'stats'));
    }

    /**
     * Detail pendaftar
     */
    public function show(Registration $registration)
    {
        $registration->load(['academicYear', 'student']);
        return view('registrations.show', compact('registration'));
    }

    /**
     * Update status pendaftaran (Verifikasi berkas, Lulus, Ditolak, Skor)
     */
    public function updateStatus(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,verified,passed,rejected',
            'selection_score' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $registration->update($validated);

        return back()->with('success', 'Status pendaftaran dan catatan berhasil diperbarui.');
    }

    /**
     * Verifikasi Slip Bukti Pembayaran Pendaftaran
     */
    public function verifyPayment(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:verified,rejected,unverified',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        $registration->update($validated);

        return back()->with('success', 'Status verifikasi pembayaran berhasil diperbarui.');
    }

    /**
     * Migrasi pendaftar yang lulus menjadi santri aktif (Enrolled)
     */
    public function enroll(Request $request, Registration $registration)
    {
        $request->validate([
            'nis' => 'required|string|unique:students,nis|max:255',
        ]);

        if ($registration->status === 'enrolled') {
            return back()->with('error', 'Calon santri ini sudah berstatus Santri Aktif.');
        }

        DB::beginTransaction();
        try {
            // Salin data pendaftaran ke tabel students
            $student = Student::create([
                'nis' => $request->nis,
                'nisn' => $registration->nisn,
                'name' => $registration->name,
                'gender' => $registration->gender,
                'birth_place' => $registration->birth_place,
                'birth_date' => $registration->birth_date,
                'child_order' => $registration->child_order,
                'nik' => $registration->nik,
                'family_card_number' => $registration->family_card_number,
                'village' => $registration->village,
                'district' => $registration->district,
                'regency' => $registration->regency,
                'province' => $registration->province,
                'father_status' => $registration->father_status,
                'father_name' => $registration->father_name,
                'father_nik' => $registration->father_nik,
                'father_phone' => $registration->father_phone,
                'father_education' => $registration->father_education,
                'father_occupation' => $registration->father_occupation,
                'father_occupation_detail' => $registration->father_occupation_detail,
                'mother_status' => $registration->mother_status,
                'mother_name' => $registration->mother_name,
                'mother_nik' => $registration->mother_nik,
                'mother_phone' => $registration->mother_phone,
                'mother_education' => $registration->mother_education,
                'mother_occupation' => $registration->mother_occupation,
                'mother_occupation_detail' => $registration->mother_occupation_detail,
                'guardian_name' => $registration->guardian_name,
                'guardian_phone' => $registration->guardian_phone,
                'guardian_education' => $registration->guardian_education,
                'guardian_occupation' => $registration->guardian_occupation,
                'guardian_occupation_detail' => $registration->guardian_occupation_detail,
                'education_level' => $registration->target_stage,
                'major' => $registration->target_major,
                'previous_school' => $registration->previous_school,
                'acceptance_date' => now()->toDateString(),
                'status' => 'active',
            ]);

            // Update status pendaftaran menjadi enrolled
            $registration->update([
                'status' => 'enrolled',
                'student_id' => $student->id,
            ]);

            DB::commit();

            return redirect()->route('students.show', $student->id)
                ->with('success', "Calon santri {$registration->name} resmi didaftarkan sebagai Santri Aktif (NIS: {$student->nis}).");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pendaftaran santri: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pendaftar
     */
    public function destroy(Registration $registration)
    {
        if ($registration->status === 'enrolled') {
            return back()->with('error', 'Pendaftar yang sudah menjadi santri aktif tidak dapat dihapus dari modul ini.');
        }

        $registration->delete();

        return redirect()->route('registrations.index')
            ->with('success', 'Data pendaftar berhasil dihapus.');
    }

    /**
     * ==============================================================
     * AREA PUBLIK (PENDAFTARAN SANTRI BARU)
     * ==============================================================
     */
    public function publicCreate()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $stages = Stage::all();

        return view('registrations.public_create', compact('activeYear', 'stages'));
    }

    /**
     * Simpan pendaftaran santri baru dari formulir publik
     */
    public function publicStore(Request $request)
    {
        $validated = $request->validate([
            // Bukti Pembayaran
            'payment_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Maks 5 MB
            'payment_sender_name' => 'required|string|max:255',
            'payment_bank_name' => 'nullable|string|max:100',
            'payment_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',

            // Data Santri & Jenjang
            'target_stage' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'nik' => 'nullable|string|size:16',
            'nisn' => 'nullable|string|max:30',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'child_order' => 'nullable|integer|min:1',
            'previous_school' => 'nullable|string|max:255',
            'family_card_number' => 'nullable|string|size:16',
            'province' => 'nullable|string|max:255',
            'regency' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'father_status' => 'nullable|in:alive,deceased',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:25',
            'father_education' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_occupation_detail' => 'nullable|string|max:255',
            'mother_status' => 'nullable|in:alive,deceased',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:25',
            'mother_education' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_occupation_detail' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:25',
            'guardian_education' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_occupation_detail' => 'nullable|string|max:255',
        ], [
            'payment_receipt.required' => 'Wajib mengunggah bukti/slip pembayaran pendaftaran.',
            'payment_receipt.mimes' => 'Format file slip pembayaran harus berupa JPG, JPEG, PNG, atau PDF.',
            'payment_receipt.max' => 'Ukuran file slip pembayaran maksimal 3 MB.',
            'payment_sender_name.required' => 'Nama pemilik rekening / pengirim transfer wajib diisi.',
            'target_stage.required' => 'Pilihan jenjang pendidikan wajib dipilih.',
            'name.required' => 'Nama lengkap calon santri wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $validated['academic_year_id'] = $activeYear?->id;
        $validated['registration_number'] = Registration::generateRegistrationNumber();
        $validated['status'] = 'pending';
        $validated['payment_status'] = 'unverified';

        // Simpan file slip transfer
        if ($request->hasFile('payment_receipt')) {
            $file = $request->file('payment_receipt');
            $filename = 'slip_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('psb_receipts', $filename, 'public');
            $validated['payment_receipt'] = $path;
        }

        // Konversi format nomor telepon (08 -> 62)
        foreach (['father_phone', 'mother_phone', 'guardian_phone'] as $phoneField) {
            if (!empty($validated[$phoneField])) {
                $phone = preg_replace('/[^0-9]/', '', $validated[$phoneField]);
                if (strpos($phone, '0') === 0) {
                    $phone = '62' . substr($phone, 1);
                }
                $validated[$phoneField] = $phone;
            }
        }

        $registration = Registration::create($validated);

        return redirect()->route('psb.success', $registration->registration_number);
    }

    /**
     * Halaman sukses pendaftaran
     */
    public function publicSuccess($regNumber)
    {
        $registration = Registration::where('registration_number', $regNumber)->firstOrFail();
        return view('registrations.public_success', compact('registration'));
    }

    /**
     * Halaman cek status pendaftaran publik
     */
    public function publicCheckStatus(Request $request)
    {
        $registration = null;
        if ($request->filled('keyword')) {
            $kw = trim($request->keyword);
            $registration = Registration::where('registration_number', $kw)
                ->orWhere('nik', $kw)
                ->first();
        }

        return view('registrations.public_check', compact('registration'));
    }

    /**
     * Cetak bukti pendaftaran (PDF)
     */
    public function printSlip(Registration $registration)
    {
        $registration->load('academicYear');
        $pdf = Pdf::loadView('registrations.slip_pdf', compact('registration'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Bukti_Pendaftaran_' . $registration->registration_number . '.pdf');
    }
}

