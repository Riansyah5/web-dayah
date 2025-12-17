<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    public function course() { return $this->belongsTo(Course::class); }
    public function student() { return $this->belongsTo(Student::class); }

    public function calculateFinal()
    {
        $final = ($this->score_harian * 0.4) + ($this->score_uts * 0.3) + ($this->score_uas * 0.3);

        $this->score_final = $final;
        $this->grade_letter = match (true) {
            $final >= 85 => 'A',
            $final >= 75 => 'B',
            $final >= 60 => 'C',
            $final >= 50 => 'D',
            default => 'E',
        };

        $this->save();
    }
}
