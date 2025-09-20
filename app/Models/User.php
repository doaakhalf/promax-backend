<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'role_id',
        'status',
    ];
    
    /**
     * The possible status values for users.
     *
     * @var array
     */
    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    
    /**
     * Get the available statuses based on user role.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        if ($this->isAthlete()) {
            return [
                self::STATUS_INCOMPLETE,
                self::STATUS_APPROVED,
            ];
        }
        
        if ($this->isCoach() || $this->isGymCoach()) {
            return [
                self::STATUS_INCOMPLETE,
                self::STATUS_PENDING,
                self::STATUS_APPROVED,
            ];
        }
        
        return [];
    }
    
    /**
     * Check if the user has the given status.
     *
     * @param  string  $status
     * @return bool
     */
    public function hasStatus($status)
    {
        return $this->status === $status;
    }
    
    /**
     * Check if the user is approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->hasStatus(self::STATUS_APPROVED);
    }
    
    /**
     * Check if the user is pending approval.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->hasStatus(self::STATUS_PENDING);
    }
    
    /**
     * Check if the user's profile is incomplete.
     *
     * @return bool
     */
    public function isIncomplete()
    {
        return $this->hasStatus(self::STATUS_INCOMPLETE);
    }
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];
    
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            // Set default status based on role
            if (empty($user->status)) {
                if ($user->isAthlete()) {
                    $user->status = self::STATUS_INCOMPLETE;
                } elseif ($user->isCoach() || $user->isGymCoach()) {
                    $user->status = self::STATUS_INCOMPLETE;
                }
            }
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'string',
    ];
    
    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    /**
     * Get the coach profile associated with the user.
     */
    public function coachProfile()
    {
        return $this->hasOne(Coach::class, 'user_id');
    }
    
    /**
     * Get the athlete profile associated with the user.
     */
    public function athleteProfile()
    {
        return $this->hasOne(Athlete::class, 'user_id');
    }
    
    /**
     * Get the certificates for the user (coach).
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'user_id');
    }
    
    /**
     * Get the workouts created by the user.
     */
    public function createdWorkouts()
    {
        return $this->hasMany(Workout::class, 'user_id');
    }
    
    /**
     * Get the workout assignments where the user is a coach.
     */
    public function assignedWorkouts()
    {
        return $this->hasMany(WorkoutAssignment::class, 'coach_id');
    }
    
    /**
     * Get the workout assignments where the user is an athlete.
     */
    public function receivedWorkouts()
    {
        return $this->hasMany(WorkoutAssignment::class, 'athlete_id');
    }
    
    /**
     * Get the subscriptions where the user is a coach.
     */
    public function coachSubscriptions()
    {
        return $this->hasMany(Subscription::class, 'coach_id');
    }
    
    /**
     * Get the subscriptions where the user is an athlete.
     */
    public function athleteSubscriptions()
    {
        return $this->hasMany(Subscription::class, 'athlete_id');
    }
    
    /**
     * Get the exercises created by the user (gym coach).
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class, 'user_id');
    }
    
    /**
     * Check if the user has a specific role.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role->name === $role;
    }
    
    /**
     * Check if the user has any of the given roles.
     *
     * @param  array  $roles
     * @return bool
     */
    public function hasAnyRole(array $roles)
    {
        return in_array($this->role->name, $roles);
    }
}
