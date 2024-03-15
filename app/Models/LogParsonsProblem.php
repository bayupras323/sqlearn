<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogParsonsProblem extends Model
{
    use HasFactory;

    protected $table = 'log_parsons_problem';

    protected $fillable = ['student_id', 'schedule_id', 'exercise_id', 'time', 'log', 'status', 'type'];

    public function exercises()
    {
        return $this->belongsTo(Exercise::class,'exercise_id','id');
    }

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'classroom_schedules', 'schedule_id', 'schedule_id');
    }

    protected $casts = [
        'log' => 'array',
    ];
}
