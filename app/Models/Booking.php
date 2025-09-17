<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'booked_at',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
    ];

    /** 
     * Many-To-One relationship for bookings to users 
     * 
     * @return User
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Many-To-One relationship for bookings to events 
     * 
     * @return Event 
     */
    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }
}
