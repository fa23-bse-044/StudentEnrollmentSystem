<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'reg_no', 'semester', 'department'];

    public function metrics()
    {
        return $this->hasMany(EvaluationMetric::class);
    }
}
