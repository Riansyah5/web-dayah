<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomAssignment extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];

    // Properti sementara untuk menampung alasan/tanggal pindah dari Controller
    public $move_reason;
    public $move_date;

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Booted: Event listener otomatis untuk sinkronisasi data ke tabel Student
     */
    protected static function booted()
    {
        // 1. Saat Created (Santri ditempatkan pertama kali)
        static::created(function ($assignment) {
            // Nonaktifkan history lama jika ada (bersihkan state sebelumnya)
            RoomHistory::where('student_id', $assignment->student_id)
                ->where('is_active', true)
                ->update(['is_active' => false, 'end_date' => Carbon::now()]);

            // Buat History Baru
            RoomHistory::create([
                'student_id' => $assignment->student_id,
                'room_id'    => $assignment->room_id,
                'start_date' => Carbon::now(),
                'is_active'  => true,
                'reason'     => 'Penempatan Awal',
            ]);
        });

        // 2. Saat Updated (Pindah Kamar)
        static::updated(function ($assignment) {
            // Cek apakah kolom room_id berubah
            if ($assignment->isDirty('room_id')) {
                // Nonaktifkan history lama
                RoomHistory::where('student_id', $assignment->student_id)
                    ->where('is_active', true)
                    ->update(['is_active' => false, 'end_date' => $assignment->move_date ?? Carbon::now()]);

                // Buat History Baru
                RoomHistory::create([
                    'student_id' => $assignment->student_id,
                    'room_id'    => $assignment->room_id,
                    'start_date' => $assignment->move_date ?? Carbon::now(),
                    'is_active'  => true,
                    'reason'     => $assignment->move_reason ?? 'Mutasi Kamar',
                ]);
            }
        });

        // 3. Sinkronisasi ke Data Siswa (String Cache) setiap kali disimpan
        static::saved(function ($assignment) {
            $assignment->loadMissing(['room.dorm', 'student']);
            if ($assignment->student && $assignment->room) {
                $assignment->student->update([
                    'dormitory' => $assignment->room->dorm->name ?? null,
                    'room' => $assignment->room->name,
                ]);
            }
        });

        // 4. Saat Deleted (Hapus Penempatan)
        static::deleted(function ($assignment) {
            // Tutup history aktif
            RoomHistory::where('student_id', $assignment->student_id)
               ->where('is_active', true)
               ->update(['is_active' => false, 'end_date' => Carbon::now(), 'reason' => 'Penempatan Dihapus']);
            
            // Kosongkan data di siswa
            if($assignment->student) {
                $assignment->student->update(['dormitory' => null, 'room' => null]);
            }
       });
    }
}
