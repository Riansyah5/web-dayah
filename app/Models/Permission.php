<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'returned_at' => 'datetime',
        'last_notification_sent_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk mengecek keterlambatan
    public function getIsLateAttribute()
    {
        if ($this->status == 'returned' && $this->returned_at) {
            return $this->returned_at->gt($this->end_date);
        }
        // Jika belum kembali dan sekarang sudah lewat deadline
        if ($this->status == 'approved' && now()->gt($this->end_date)) {
            return true;
        }
        return false;
    }
}
