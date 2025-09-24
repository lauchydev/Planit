<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;
    
    public function store(Event $event): RedirectResponse
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        /**
         * See {@link EventPolicy} - All Policies Return a Boolean on whether the user can perform that action
         * @param Event $event
         * @return boolean
         */
        if (!$user->can('book', $event)) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You are not allowed to book this event.');
        }

        if ($event->hasStarted()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'This event has already started.');
        }

        if ($event->isFull()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'We are sorry, this event is full. :(');
        }

        if ($user->bookings()->where('event_id', $event->id)->exists()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You have already booked this event.');
        }

        /* Create the booking */
        $event->bookings()->create([
            'user_id'   => $user->id,
            'booked_at' => now(),
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'Booking confirmed!');
    }
}
