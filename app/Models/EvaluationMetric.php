<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationMetric extends Model
{
    protected $fillable = ['student_id', 'course_no', 'marks'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
