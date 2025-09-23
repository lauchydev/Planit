<?php

namespace App\Http\Controllers;

use Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
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

        $events = $query->paginate(12)->withQueryString();

        return view('events.index', compact('events'));
    }

    public function show(Event $event) {
        $event->load('organiser', 'bookings.user');
        
        return view('events.show', compact('event'));
    }


}
