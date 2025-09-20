<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workout extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'workout_type',
        'name',
        'description',
        'instructions',
        'is_template',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_template' => 'boolean',
    ];
    
    /**
     * Get the user that owns the workout.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the coach that created the workout.
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class, 'user_id', 'user_id');
    }
    
    /**
     * Get the sections for the workout (for coach workouts).
     */
    public function sections()
    {
        return $this->hasMany(CoachWorkoutSection::class, 'workout_id');
    }
    
    /**
     * Get the sets for the workout (for gym coach workouts).
     */
    public function sets()
    {
        return $this->hasMany(GymWorkoutSet::class, 'workout_id');
    }
    
    /**
     * Get the media items for the workout.
     */
    public function media()
    {
        return $this->morphMany(WorkoutMedia::class, 'mediaable');
    }
    
    /**
     * Get the assignments for the workout.
     */
    public function assignments()
    {
        return $this->hasMany(WorkoutAssignment::class);
    }
}
