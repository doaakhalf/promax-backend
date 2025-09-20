<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkoutAssignment extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coach_id',
        'athlete_id',
        'workout_id',
        'scheduled_date',
        'status',
        'coach_notes',
        'athlete_feedback',
        'started_at',
        'completed_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    
    /**
     * Get the coach who assigned the workout.
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    
    /**
     * Get the athlete who received the workout.
     */
    public function athlete()
    {
        return $this->belongsTo(User::class, 'athlete_id');
    }
    
    /**
     * Get the assigned workout.
     */
    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
    
    /**
     * Mark the workout as started.
     *
     * @return bool
     */
    public function markAsStarted()
    {
        if (!$this->started_at) {
            $this->status = 'in_progress';
            $this->started_at = now();
            return $this->save();
        }
        
        return false;
    }
    
    /**
     * Mark the workout as completed.
     *
     * @param  string|null  $feedback
     * @return bool
     */
    public function markAsCompleted($feedback = null)
    {
        if ($feedback) {
            $this->athlete_feedback = $feedback;
        }
        
        $this->status = 'completed';
        $this->completed_at = now();
        
        return $this->save();
    }
}
