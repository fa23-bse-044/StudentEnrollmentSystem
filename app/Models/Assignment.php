<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'assignments';
    protected $fillable = ['course_no', 'title', 'description', 'start_date', 'last_date', 'student_id', 'file_path', 'marks'];
}
