<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachWorkoutStep extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'section_id',
        'step_number',
        'step_type',
        'order',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['details'];
    
    /**
     * Get the section that owns the step.
     */
    public function section()
    {
        return $this->belongsTo(CoachWorkoutSection::class, 'section_id');
    }
    
    /**
     * Get the details for the step.
     */
    public function detail()
    {
        return $this->hasOne(CoachWorkoutStepDetail::class, 'step_id');
    }
    
    /**
     * Get the step details (alias for detail for better readability).
     */
    public function getDetailsAttribute()
    {
        return $this->detail;
    }
}
