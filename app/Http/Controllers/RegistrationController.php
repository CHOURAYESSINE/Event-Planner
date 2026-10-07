<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RegistrationController extends Controller
{
      public function store(Event $event)
    {
        $user = auth()->user();

        // block admins
        if ($user->role === 'admin') {
            return back()->with('error', 'Admins cannot book events.');
        }

        // avoid double booking
        $alreadyBooked = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->exists();

        if ($alreadyBooked) {
            return back()->with('error', 'You already booked this event.');
        }

        try {
            DB::transaction(function () use ($event, $user) {

                // decrement capacity only if > 0
                $updated = Event::where('id', $event->id)
                    ->where('capacity', '>', 0)
                    ->decrement('capacity', 1);

                if ($updated === 0) {
                    throw new \Exception('No places left.');
                }

                Registration::create([
                    'user_id'  => $user->id,
                    'event_id' => $event->id,
                ]);
            });

            return back()->with('success', 'Booking confirmed.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Booking failed', ['reason' => $e->getMessage()]);
            return back()->with('error', $e->getMessage() === 'No places left.'
                ? 'No places left for this event.'
                : 'Booking failed.');
        }
    }

    public function destroy(Event $event)
    {
        $user = auth()->user();

        // block admins (optional but consistent)
        if ($user->role === 'admin') {
            return back()->with('error', 'Admins cannot manage bookings.');
        }

        $registration = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$registration) {
            return back()->with('error', 'You have no booking for this event.');
        }

        DB::transaction(function () use ($event, $registration) {
            $registration->delete();
            Event::where('id', $event->id)->increment('capacity', 1);
        });

        return back()->with('success', 'Booking canceled.');
    }
}
