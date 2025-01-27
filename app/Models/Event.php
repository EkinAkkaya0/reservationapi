<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'venue_id', 'start_date', 'end_date', 'status',
    ];

    /**
     * Event'in bağlı olduğu venue'yu al.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class, 'venue_id', 'venue_id');
    }
}
