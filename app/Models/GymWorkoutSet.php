<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GymWorkoutSet extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'workout_id',
        'exercise_id',
        'order',
        'notes',
    ];
    
    /**
     * Get the workout that owns the set.
     */
    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
    
    /**
     * Get the exercise for the set.
     */
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
    
    /**
     * Get the set details.
     */
    public function details()
    {
        return $this->hasMany(GymWorkoutSetDetail::class, 'set_id');
    }
    
    /**
     * Get the primary detail (first one if multiple exist).
     */
    public function detail()
    {
        return $this->hasOne(GymWorkoutSetDetail::class, 'set_id');
    }
}
