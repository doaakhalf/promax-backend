<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkoutMedia extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'file_path',
        'file_type',
        'title',
        'description',
        'order',
    ];
    
    /**
     * Get the parent mediaable model (workout, coach workout, gym workout, etc.).
     */
    public function mediaable()
    {
        return $this->morphTo();
    }
    
    /**
     * Get the URL for the media file.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
    
    /**
     * Check if the media is an image.
     *
     * @return bool
     */
    public function getIsImageAttribute()
    {
        return in_array($this->file_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }
    
    /**
     * Check if the media is a video.
     *
     * @return bool
     */
    public function getIsVideoAttribute()
    {
        return strpos($this->file_type, 'video/') === 0;
    }
}
