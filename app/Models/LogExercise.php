<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogExercise extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'schedule_id', 'exercise_id', 'time', 'answer', 'status', 'confident'];

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
}
