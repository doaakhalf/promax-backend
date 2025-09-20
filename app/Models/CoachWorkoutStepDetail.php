<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoachWorkoutStepDetail extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'step_id',
        'duration_type',
        'duration_value',
        'target_type',
        'target_min',
        'target_max',
        'instructions',
        'repeat_count',
        'step_category',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duration_value' => 'decimal:2',
        'target_min' => 'decimal:2',
        'target_max' => 'decimal:2',
        'repeat_count' => 'integer',
    ];
    
    /**
     * Get the step that owns the detail.
     */
    public function step()
    {
        return $this->belongsTo(CoachWorkoutStep::class, 'step_id');
    }
    
    /**
     * Get the formatted target range.
     *
     * @return string|null
     */
    public function getFormattedTargetAttribute()
    {
        if ($this->target_type === 'none') {
            return null;
        }
        
        if ($this->target_min === $this->target_max) {
            return "{$this->target_min} {$this->target_type}";
        }
        
        return "{$this->target_min}-{$this->target_max} {$this->target_type}";
    }
}
