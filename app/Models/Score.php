<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static updateOrCreate(array $array, array $array1)
 */
class Score extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'schedule_id', 'score'];

    /**
     * Get all of the students for the Classroom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * Get all of the students for the Classroom
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'classroom_schedules', 'schedule_id', 'schedule_id');
    }
}
