<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahfizhAttendance extends Model
{
    protected $guarded = ['id'];

    /**
     * RELASI
     */

    // Ke Header Jurnal
    public function journal(): BelongsTo
    {
        return $this->belongsTo(TahfizhJournal::class, 'tahfizh_journal_id');
    }

    // Ke Data Santri
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}