<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahfizhSetoran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'date' => 'date',
    ];

    public function student() { return $this->belongsTo(Student::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
    
    // Relasi ke Tabel Surat (Untuk menampilkan nama surat)
    public function surahStart() { return $this->belongsTo(QuranSurah::class, 'surah_start_id'); }
    public function surahEnd() { return $this->belongsTo(QuranSurah::class, 'surah_end_id'); }

    // Helper: Menampilkan string lokasi hafalan (Misal: Al-Baqarah: 1-5)
    public function getLocationAttribute()
    {
        if ($this->surah_start_id == $this->surah_end_id) {
            return $this->surahStart->name_latin . ': ' . $this->ayat_start . '-' . $this->ayat_end;
        }
        return $this->surahStart->name_latin . ':' . $this->ayat_start . ' s/d ' . $this->surahEnd->name_latin . ':' . $this->ayat_end;
    }
}
