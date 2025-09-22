<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* Create 2 organisers */
        $organisers = User::factory()->organiser()->count(2)->create([
            'password' => bcrypt('password123'),
        ]);

        /* Create some attendees for demo */
        $attendees = User::factory()->attendee()->count(10)->create([
            'password' => bcrypt('password123'),
        ]);

        /* Create some past events */
        Event::factory()->past()->count(3)->create(['organiser_id' => $organisers[0]->id]);
        Event::factory()->past()->count(2)->create(['organiser_id' => $organisers[1]->id]);

        /* Create some upcoming events */
        Event::factory()->count(8)->create(['organiser_id' => $organisers[0]->id]);
        Event::factory()->count(7)->create(['organiser_id' => $organisers[1]->id]);


        /* Get the list of upcoming events */
        $upcomingEvents = Event::where('start_time', '>', now())->get();

        /* Add some bookings for the upcoming events */
        foreach ($upcomingEvents as $event) {

            $numAttendees = rand(1, $event->capacity);

            $randomAttendees = $attendees->random(min($numAttendees, $attendees->count()));

            foreach ($randomAttendees as $attendee) {
                Booking::create([
                    'user_id' => $attendee->id,
                    'event_id' => $event->id,
                    'booked_at' => now(),
                ]);
            }
        }

        /* Create a full event for case handling */
        $fullEvent = $upcomingEvents->first();
        if ($fullEvent) {
            $fullEvent->bookings()->delete();
    
            /* Check if there is enough attendees to fill event */
            if ($attendees->count() < $fullEvent->capacity) {
                $additionalAttendees = User::factory()
                ->attendee()
                ->count($fullEvent->capacity - $attendees->count())
                ->create([
                    'password' => bcrypt('password123'),
                ]);
                $attendees = $attendees->merge($additionalAttendees);
            }
    
            /* Create 'capacity' bookings for the event (fill it) */
            foreach ($attendees->take($fullEvent->capacity) as $attendee) {
                Booking::create([
                    'user_id' => $attendee->id,
                    'event_id' => $fullEvent->id,
                    'booked_at' => now(),
                ]);
            }
        }
    }
}
