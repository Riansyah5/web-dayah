<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TeacherPermissionDetail extends Model
{
    protected $guarded = ['id'];

    public function permission()
    {
        return $this->belongsTo(TeacherPermission::class, 'teacher_permission_id');
    }
}