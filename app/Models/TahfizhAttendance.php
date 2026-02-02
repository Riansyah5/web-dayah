<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahfizhAttendance extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    /**
     * Get the journal that this attendance record belongs to.
     */
    public function tahfizhJournal(): BelongsTo
    {
        return $this->belongsTo(TahfizhJournal::class);
    }

    /**
     * Get the student that this attendance record belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}