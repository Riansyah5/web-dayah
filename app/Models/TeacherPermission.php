<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherPermission extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['date' => 'date'];

    public function teacher() { return $this->belongsTo(Teacher::class); }
}