<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachWorkoutSection extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'workout_id',
        'section_type',
        'order',
    ];
    
    /**
     * Get the workout that owns the section.
     */
    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
    
    /**
     * Get the steps for the section.
     */
    public function steps()
    {
        return $this->hasMany(CoachWorkoutStep::class, 'section_id');
    }
    
    /**
     * Get the section name based on the section type.
     *
     * @return string
     */
    public function getSectionNameAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->section_type));
    }
}
