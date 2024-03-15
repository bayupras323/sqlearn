<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'classrooms_id', 'student_id_number'];

    public function classrooms()
    {
        return $this->belongsTo(Classroom::class, 'classrooms_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,  'user_id', 'id')->orderBy('name');
    }

    /**
     * The students that has many Score
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function log_exercises()
    {
        return $this->hasMany(LogExercise::class);
    }
}
