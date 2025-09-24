<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isOrganiser();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->isOrganiser() && $user->id === $event->organiser_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        /* There must not be any bookings for the organiser to delete the event */
        return $user->isOrganiser() && 
            $user->id === $event->organiser_id && 
            
            $event->bookings()->count() === 0;
    }

    /**
     * Determine whether the user can book an event
     */
    public function book(User $user, Event $event): bool
    {
        return $user->isAttendee() && 
            !$event->hasStarted() && 
            !$event->isFull() && 
            !$user->bookings()->where('event_id', $event->id)->exists();
    }
}
