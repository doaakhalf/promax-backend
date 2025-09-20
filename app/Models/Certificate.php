<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'certificate_name',
        'year',
        'certificate_image',
    ];
    
    /**
     * Get the user that owns the certificate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the coach that owns the certificate.
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class, 'user_id', 'user_id');
    }
}
