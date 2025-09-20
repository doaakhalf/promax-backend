<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coach extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'sport',
        'best_record',
        'introduction',
        'training_experience',
        'motivation',
        'headline',
        'photo',
        'video_url',
        'monthly_price_egp',
        'instapay_link',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'best_record' => 'array',
    ];
    
    /**
     * Get the user that owns the coach profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the workouts created by this coach.
     */
    public function workouts()
    {
        return $this->hasMany(Workout::class, 'user_id', 'user_id');
    }
    
    /**
     * Get the certificates for the coach.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'user_id', 'user_id');
    }
    
    /**
     * Get the athletes subscribed to this coach.
     */
    public function athletes()
    {
        return $this->hasManyThrough(
            User::class,
            Subscription::class,
            'coach_id',
            'id',
            'user_id',
            'athlete_id'
        )->where('status', 'active');
    }
    
    /**
     * Get the active subscriptions for this coach.
     */
    public function activeSubscriptions()
    {
        return $this->hasMany(Subscription::class, 'coach_id', 'user_id')
            ->where('status', 'active');
    }
}
