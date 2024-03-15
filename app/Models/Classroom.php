<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Classroom extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = ['name', 'user_id', 'semester'];

    /**
     * Get all of the students for the Classroom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'classrooms_id', 'id');
    }

    /**
     * The schedules that belong to the Classroom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'classroom_schedules');
    }
}
