<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'location', 'address', 'description', 'reporter', 
        'scale', 'status', 'source', 'lat', 'lng'
    ];

    protected $casts = [
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'created_at' => 'datetime',
    ];

    public function getFormattedDateAttribute()
    {
        $now = now();
        $diffDays = $now->diffInDays($this->created_at);
        
        if ($diffDays === 0) return 'Hari ini';
        if ($diffDays === 1) return 'Kemarin';
        if ($diffDays <= 7) return $diffDays . ' hari lalu';
        return $this->created_at->format('d M Y');
    }
}