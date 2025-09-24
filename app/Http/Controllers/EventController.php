<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request) {
        $query = Event::query()
            ->where('start_time', '>', now())
            ->with('organiser')
            ->orderBy('start_time');


        /* If someone searches for an event by title/description/location  */
        if($request->filled('search')) {
            $search = $request->search;
            /* SQL Query building */
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->where('start_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('start_time', '<=', $request->date_to . ' 23:59:59');
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        $events = $query->paginate(8)->withQueryString();

        return view('events.index', compact('events'));
    }

    /* Show Event Details */
    public function details(Event $event) {
        $event->load('organiser', 'bookings.user');
        
        return view('events.details', compact('event'));
    }

    /* Create Event */
    public function create()
    {
        $this->authorize('create', Event::class);
        return view('events.create');
    }

    /* Handle Event Creation */
    public function handleCreate(StoreEventRequest $request)
    {
        $data = $request->validated();
        $data['organiser_id'] = auth()->id();
        $event = Event::create($data);
        return redirect()->route('events.details', $event)->with('success', 'Event Created Successfully');
    }

    /* Update Event */
    public function update(Event $event)
    {
        $this->authorize('update', $event);
        return view('events.edit', compact('event'));
    }

    /* Handle Event Update */
    public function handleUpdate(UpdateEventRequest $request, Event $event) {
        $data = $request->validated();
        $event->update($data);
        return redirect()->route('events.details', $event)->with('success', 'Event Updated Successfully');
    }

    /* Delete Event */
    public function delete(Event $event) {
        $this->authorize('delete', $event);

        /* Check if bookings exist before deleting */
        if ($event->bookings()->exists()) {
            return redirect()->route('events.details', $event)
                ->with('error', 'Cannot delete an event that has bookings.');
        }
        $event->delete();
        return redirect()->route('home')->with('success', 'Event Deleted Successfully');
    }

    /* Organiser's events list */
    public function myEvents(Request $request)
    {
        $user = $request->user();
        abort_unless($user && $user->isOrganiser(), 403);

        $query = Event::query()
            ->where('organiser_id', $user->id)
            ->withCount('bookings')
            ->orderBy('start_time');

        $events = $query->paginate(10)->withQueryString();
        return view('events.my-events', compact('events'));
    }


}
