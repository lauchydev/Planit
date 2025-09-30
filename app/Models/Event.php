<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'capacity',
        'organiser_id',
    ];

    /* Data casting for the database */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    protected $appends = [
        'starts_at',
        'ends_at',
    ];

    /**
     * Many-to-one relationship where many events can belong to one user 
     * 
     * @return User
     */
    public function organiser(): BelongsTo {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    /**
     * One-To-Many relationship for all bookings this event has
     * 
     * @return Booking
     */
    public function bookings(): HasMany {
        return $this->hasMany(Booking::class);
    }

    /**
     * Many-To-Many relationship for all the users to their respective bookings
     * 
     * @return User
     */
    public function attendees(): BelongsToMany {
        return $this->belongsToMany(User::class, 'bookings')->withTimestamps();
    }

    /**
     * Boolean function to check if an event is full
     * 
     * @return boolean
     */
    public function isFull(): bool {
        return $this->bookings()->count() >= $this->capacity;
    }

    /**
     * Boolean function to check if an event has started
     * 
     * @return boolean
     */
    public function hasStarted(): bool {
        return $this->start_time <= now();
    }

    /**
     * Function to check how much space is left in the event
     * 
     * @return int
     */
    public function availableSpaces(): int {
        return max(0, $this->capacity - $this->bookings()->count());
    }

    /**
     * Helper function for starting at time
     * 
     * @return datetime
     */
    public function startsAt() {
        return $this->start_time;
    }

    /**
     * Helper function for ending at time
     * 
     * @return datetime
     */
    public function endsAt() {
        return $this->end_time;
    }

    /**
     * Many-to-many relationship for event tags
     * 
     * @return Tag
     */

    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Filter events that have any of the given tags (OR)
     */
    public function scopeWithAnyTags($query, array $tagIds)
    {
        return $query->whereHas('tags', function ($q) use ($tagIds) {
            $q->whereIn('tags.id', $tagIds);
        });
    }

    /**
     * Filter events that have all of the given tags (AND)
     */
    public function scopeWithAllTags($query, array $tagIds)
{
    foreach ($tagIds as $tagId) {
        $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('tags.id', $tagId);
        });
    }
    return $query;
}



}
