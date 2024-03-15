<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Exercise extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['package_id', 'type', 'ddl_type', 'database_id', 'question', 'answer'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'answer' => 'json',
    ];

    /**
     * Get the package that owns the Exercise
     *
     * @return BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the database that owns the Practice
     *
     * @return BelongsTo
     */
    public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class);
    }

    /**
     * Get all the additions for the Package
     *
     * @return HasMany
     */
    public function additions(): HasMany
    {
        return $this->hasMany(Addition::class);
    }

    public function log_exercises()
    {
        return $this->hasMany(LogExercise::class);
    }
}
