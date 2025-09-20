<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Athlete extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'photo',
        'gender',
        'weight',
        'training_frequency',
        'inbody_file',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weight' => 'decimal:2',
    ];
    
    /**
     * Get the user that owns the athlete profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the workout assignments for the athlete.
     */
    public function workoutAssignments()
    {
        return $this->hasMany(WorkoutAssignment::class, 'athlete_id', 'user_id');
    }
    
    /**
     * Get the coaches this athlete is subscribed to.
     */
    public function coaches()
    {
        return $this->hasManyThrough(
            User::class,
            Subscription::class,
            'athlete_id',
            'id',
            'user_id',
            'coach_id'
        )->where('status', 'active');
    }
    
    /**
     * Get the active subscriptions for this athlete.
     */
    public function activeSubscriptions()
    {
        return $this->hasMany(Subscription::class, 'athlete_id', 'user_id')
            ->where('status', 'active');
    }
}
