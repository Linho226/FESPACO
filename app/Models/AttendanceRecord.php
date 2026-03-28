<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'projection_id',
        'spectators_count',
        'available_seats',
        'occupancy_rate',
    ];

    protected $casts = [
        'spectators_count' => 'integer',
        'available_seats' => 'integer',
        'occupancy_rate' => 'float',
    ];

    public function projection(): BelongsTo
    {
        return $this->belongsTo(Projection::class);
    }
}
