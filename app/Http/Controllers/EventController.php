<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request) {
        $query = Event::query()
            ->with(['organiser', 'tags'])
            ->orderBy('start_time', 'asc')->orderBy('id', 'asc');

        /* Scope filtering */
        $scope = $request->string('scope')->toString();
        if ($scope === 'past') {
            $query->where('start_time', '<=', now());
        } elseif ($scope !== 'all') {
            $query->where('start_time', '>', now());
        }

        /* Text search across title/location/description */
        if ($search = trim($request->string('search')->toString())) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        /* Organiser filter */
        if ($organiserId = $request->integer('organiser_id')) {
            $query->where('organiser_id', $organiserId);
        }

        /* Non AJAX tag filter */
        $tagIds = $request->input('tags', []); 
        if (!empty($tagIds)) {
            $query->withAnyTags($tagIds);
        }

        $events = $query->paginate(8)->withQueryString();
        $tags   = Tag::orderBy('name')->get();
        $organisers = User::whereHas('organisedEvents')
            ->orderBy('name')
            ->get(['id','name']);

        return view('events.index', compact('events', 'tags', 'organisers'));
    }

    public function filter(Request $request)
    {
        $query = Event::query()
            ->with(['organiser', 'tags'])
            ->orderBy('start_time', 'asc')->orderBy('id', 'asc');

        $scope = $request->string('scope')->toString();
        if ($scope === 'past') {
            $query->where('start_time', '<=', now());
        } elseif ($scope !== 'all') {
            $query->where('start_time', '>', now());
        }

        if ($search = trim($request->string('search')->toString())) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($organiserId = $request->integer('organiser_id')) {
            $query->where('organiser_id', $organiserId);
        }

        /* AJAX Tag Filter */
        $tagIds = $request->input('tags', []);
        if (!empty($tagIds)) {
            $query->withAnyTags($tagIds);
        }

        $events = $query->paginate(8)->withQueryString();

        $html = view('events.partials.list', compact('events'))->render();
        return response()->json(['html' => $html]);
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
    return redirect()->route('events.index')->with('success', 'Event Deleted Successfully');
    }



}
