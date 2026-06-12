<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $fillable = ['name', 'email', 'profile_picture'];

    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'teacher_id');
    }
}
