<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GymWorkoutSetDetail extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'set_id',
        'duration_type',
        'duration_value',
        'sets',
        'reps',
        'rest_seconds',
        'weight_type',
        'weight_kg',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duration_value' => 'decimal:2',
        'sets' => 'integer',
        'reps' => 'integer',
        'rest_seconds' => 'integer',
        'weight_kg' => 'decimal:2',
    ];
    
    /**
     * Get the set that owns the detail.
     */
    public function set()
    {
        return $this->belongsTo(GymWorkoutSet::class, 'set_id');
    }
    
    /**
     * Get the formatted weight with type.
     *
     * @return string
     */
    public function getFormattedWeightAttribute()
    {
        return "{$this->weight_kg} kg ({$this->weight_type})";
    }
    
    /**
     * Get the formatted set details.
     *
     * @return string
     */
    public function getFormattedDetailsAttribute()
    {
        $details = [];
        
        if ($this->duration_type === 'time') {
            $details[] = "{$this->duration_value} sec";
        } else {
            $details[] = "{$this->sets} × {$this->reps} reps";
        }
        
        if ($this->weight_kg) {
            $details[] = $this->formatted_weight;
        }
        
        if ($this->rest_seconds) {
            $details[] = "{$this->rest_seconds}s rest";
        }
        
        return implode(' • ', $details);
    }
}
