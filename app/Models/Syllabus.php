<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory, HasUlids;
    protected $table = 'syllabi'; // Fix pluralization
    protected $guarded = ['id'];

    public function subject() { return $this->belongsTo(Subject::class); }
    public function level() { return $this->belongsTo(Level::class); }
}
