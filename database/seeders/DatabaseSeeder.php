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

        /* Create an Organiser and User for testing */
        User::updateOrCreate(
            ['email' => 'organiser@email.com'],
            [
                'name' => 'Dr. Lauchlan Thomson',
                'role' => 'organiser',
                'password' => bcrypt('password'),
            ],
        );
        User::updateOrCreate(
            ['email' => 'user@email.com'],
            [
                'name' => 'David Chen',
                'role' => 'attendee',
                'password' => bcrypt('password'),
            ],
        );



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
        $upcoming = Event::where('start_time','>', now())->get();
        foreach ($upcoming as $event) {
            $toBook = min($event->capacity - 1, rand(0, floor($event->capacity * 0.5)));
            if ($toBook <= 0) continue;
            $attendees->random(min($toBook, $attendees->count()))
                ->each(fn($user) => $event->bookings()->create([
                    'user_id' => $user->id,
                    'booked_at' => now(),
                ]));
        }

        /* Create a full event for case handling */
        $fullEvent = $upcoming->first();
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

        /* Seed the tags as they arent being automatically filled with a factory */
        $this->call(TagSeeder::class);
    }
}
