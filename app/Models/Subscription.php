<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
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
        'subscription_plan',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'transaction_id',
        'start_date',
        'end_date',
        'renewal_date',
        'status',
        'metadata',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'renewal_date' => 'date',
        'metadata' => 'array',
    ];
    
    /**
     * Get the coach who provides the subscription.
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    
    /**
     * Get the athlete who is subscribed.
     */
    public function athlete()
    {
        return $this->belongsTo(User::class, 'athlete_id');
    }
    
    /**
     * Get the coach's profile for this subscription.
     */
    public function coachProfile()
    {
        return $this->belongsTo(Coach::class, 'coach_id', 'user_id');
    }
    
    /**
     * Check if the subscription is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active' && $this->end_date->isFuture();
    }
    
    /**
     * Check if the subscription is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->status === 'expired' || $this->end_date->isPast();
    }
    
    /**
     * Get the formatted amount with currency.
     *
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        $symbols = [
            'EGP' => 'E£',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
        ];
        
        $symbol = $symbols[$this->currency] ?? $this->currency;
        
        return $symbol . number_format($this->amount, 2);
    }
    
    /**
     * Get the subscription duration in days.
     *
     * @return int
     */
    public function getDurationInDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date);
    }
}
