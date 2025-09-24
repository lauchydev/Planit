<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * A user can cancel their own booking if the event has not started.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        $event = $booking->event;
        return $user->id === $booking->user_id && $event && !$event->hasStarted();
    }
}
