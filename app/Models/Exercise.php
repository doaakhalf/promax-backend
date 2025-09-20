<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exercise extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'type',
        'target_body_parts',
        'image_path',
        'video_url',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'target_body_parts' => 'array',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name', 'description'];
    
    /**
     * Get the user that owns the exercise.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the workout sets that include this exercise.
     */
    public function workoutSets()
    {
        return $this->hasMany(GymWorkoutSet::class);
    }
    
    /**
     * Get the localized name based on current locale.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }
    
    /**
     * Get the localized description based on current locale.
     *
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }
}
