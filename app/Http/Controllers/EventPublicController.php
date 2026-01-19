<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventPublicController extends Controller
{
    // GET /events
    public function index()
    {
        $events = Event::where('status', 'active')
            ->orderBy('start_date', 'asc')
            ->paginate(10);

        return view('events.index', compact('events'));
    }

   

    public function show(Event $event)
{
    $otherEvents = \App\Models\Event::where('id', '!=', $event->id)
        ->where('status', 'active')
        ->latest()
        ->take(6)
        ->get();

    $alreadyBooked = auth()->check()
        ? $event->registrations()->where('user_id', auth()->id())->exists()
        : false;

    return view('events.show', compact('event', 'otherEvents', 'alreadyBooked'));
}

}
