<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Schedule extends Model
{
    use HasFactory, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['package_id', 'name', 'type', 'start_date', 'end_date'];

    /**
     * The classrooms that belong to the Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_schedules');
    }

    /**
     * Get the package that owns the Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * The classrooms that has many Score
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    /**
     * The log_exercises that has many Score
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function log_exercises()
    {
        return $this->hasMany(LogExercise::class);
    }
}
