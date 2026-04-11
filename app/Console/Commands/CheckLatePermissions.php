<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permission;
use App\Models\RoomAssignment;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CheckLatePermissions extends Command
{
    // Nama command untuk dipanggil di terminal
    protected $signature = 'permissions:check-late';
    protected $description = 'Mengecek santri yang terlambat kembali dari izin dan mengirim notifikasi WA ke Musyrif/Wali Kamar';

    public function handle()
    {
        // 1. Ambil izin yang masih "approved" (belum kembali) dan batas waktu sudah terlewati (terlambat)
        $latePermissions = Permission::with('student')
            ->where('status', 'approved')
            ->where('end_date', '<', now())
            ->get();

        $count = 0;

        foreach ($latePermissions as $permission) {
            // Cek apakah notifikasi sudah dikirim hari ini. Jika sudah, lewati.
            if ($permission->last_notification_sent_at && $permission->last_notification_sent_at->isToday()) {
                continue;
            }

            $student = $permission->student;

            // 2. Cari Kamar & Warden (Wali Kamar) Santri Tersebut melalui RoomAssignment
            $assignment = RoomAssignment::with('room.warden')
                ->where('student_id', $student->id)
                ->latest()
                ->first();

            $warden = $assignment->room->warden ?? null;

            // Cek apakah Warden ada dan punya nomor HP
            if ($warden && !empty($warden->no_hp)) {
                $this->sendWhatsApp($warden->no_hp, $student, $permission, $assignment->room->name ?? '-');
                $count++;

                // Update timestamp notifikasi setelah berhasil dikirim
                $permission->update(['last_notification_sent_at' => now()]);
            }
        }

        $this->info("Pengecekan selesai. {$count} notifikasi WhatsApp berhasil dikirim ke Wali Kamar.");
    }

    private function sendWhatsApp($phone, $student, $permission, $roomName)
    {
        $message = "*PEMBERITAHUAN SANTRI TERLAMBAT KEMBALI*\n\n"
            . "Assalamu'alaikum Wr. Wb.\n"
            . "Diberitahukan kepada Musyrif/Wali Kamar, bahwa santri berikut:\n\n"
            . "Nama : *" . $student->name . "*\n"
            . "Kamar : *" . $roomName . "*\n"
            . "Alasan Izin : " . $permission->reason . "\n"
            . "Batas Waktu : *" . Carbon::parse($permission->end_date)->translatedFormat('d F Y H:i') . "*\n\n"
            . "Telah melewati batas waktu izin dan belum tercatat kembali ke pondok. Mohon untuk segera ditindaklanjuti.\n\n"
            . "Terima Kasih.";

        // Menggunakan API Fonnte (Free 1000 message/hari)
        // Pastikan Anda sudah mendaftar di fonnte.com dan menaruh tokennya di file .env
        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN', '')
        ])->post('https://api.fonnte.com/send', [
            'target' => $phone,
            'message' => $message,
            'countryCode' => '62', // Otomatis menyesuaikan meski format awalnya 08 atau 62
        ]);
    }
}