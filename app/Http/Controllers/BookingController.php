<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;
    /**
     * Show the user's bookings
     */
    public function index()
    {
        $user = auth()->user();
        $bookings = $user->bookings()
            ->with(['event.organiser'])
            ->whereHas('event')
            ->orderBy(Event::select('start_time')->whereColumn('events.id', 'bookings.event_id'))
            ->paginate(8);
        return view('bookings.index', compact('bookings'));
    }
    
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
            return redirect()->route('events.details', $event)
                ->with('error', 'You are not allowed to book this event.');
        }

        if ($event->hasStarted()) {
            return redirect()->route('events.details', $event)
                ->with('error', 'This event has already started.');
        }

        if ($event->isFull()) {
            return redirect()->route('events.details', $event)
                ->with('error', 'We are sorry, this event is full. :(');
        }

        if ($user->bookings()->where('event_id', $event->id)->exists()) {
            return redirect()->route('events.details', $event)
                ->with('error', 'You have already booked this event.');
        }

        /* Create the booking */
        $event->bookings()->create([
            'user_id'   => $user->id,
            'booked_at' => now(),
        ]);

        return redirect()->route('events.details', $event)
            ->with('success', 'Booking confirmed!');
    }

    public function delete(Event $event, Booking $booking): RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->can('cancel', $booking)) {
            return redirect()->route('events.details', $event)
                ->with('error', 'You cannot cancel this booking.');
        }

        $booking->delete();
        return redirect()->route('events.details', $event)
            ->with('success', 'Booking cancelled.');
    }
}
