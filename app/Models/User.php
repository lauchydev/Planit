<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Helper functions to check roles
     * @return boolean
     */

    public function isOrganiser(): bool {
        return $this->role === 'organiser';
    }

    public function isAttendee(): bool {
        return $this->role === 'attendee';
    }


    /**
     * One-To-Many relationship for all the events this user has organised 
     * 
     * @return Event
     */
    public function organisedEvents(): HasMany {
        return $this->hasMany(Event::class, 'organiser_id');
    }

    /** 
     * One-To-Many relationship for all the bookings made by this user 
     * 
     * @return Booking
     */
    public function bookings(): HasMany {
        return $this->hasMany(Booking::class);
    }

    /** 
     * Many-To-Many relationship for all the events this user has booked 
     * 
     * @return Event
     */
    public function bookedEvents(): BelongsToMany {
        return $this->belongsToMany(Event::class, 'bookings')->withTimestamps();
    }

}
