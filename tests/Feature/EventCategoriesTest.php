<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_organiser_can_assign_tags_during_event_creation(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $tags = Tag::factory()->count(3)->create();

        $this->actingAs($organiser);

        $eventData = [
            'title' => 'Tagged Event',
            'description' => 'This is a comprehensive event description that meets the minimum requirement of 20 characters with tags',
            'start_time' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'end_time' => now()->addDays(7)->addHours(2)->format('Y-m-d\TH:i'),
            'location' => 'Test Location',
            'capacity' => 50,
            'tags' => [$tags[0]->id, $tags[1]->id],
        ];

        $response = $this->post('/events', $eventData);

        $event = Event::where('title', 'Tagged Event')->first();
        $this->assertNotNull($event);
        
        // Verify tags were attached
        $this->assertTrue($event->tags->contains($tags[0]));
        $this->assertTrue($event->tags->contains($tags[1]));
        $this->assertFalse($event->tags->contains($tags[2]));
        
        $response->assertRedirect("/events/{$event->id}");
    }

    public function test_an_organiser_can_update_event_tags(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $tags = Tag::factory()->count(3)->create();
        
        $event = Event::factory()->create(['organiser_id' => $organiser->id]);
        $event->tags()->attach([$tags[0]->id]);

        $this->actingAs($organiser);

        $updateData = [
            'title' => $event->title,
            'description' => $event->description,
            'start_time' => $event->start_time->format('Y-m-d\TH:i'),
            'end_time' => $event->end_time->format('Y-m-d\TH:i'),
            'location' => $event->location,
            'capacity' => $event->capacity,
            'tags' => [$tags[1]->id, $tags[2]->id], // Change tags
        ];

        $response = $this->put("/events/{$event->id}", $updateData);

        $event->refresh();
        
        // Verify old tag was removed and new tags were added
        $this->assertFalse($event->tags->contains($tags[0]));
        $this->assertTrue($event->tags->contains($tags[1]));
        $this->assertTrue($event->tags->contains($tags[2]));
        
        $response->assertRedirect("/events/{$event->id}");
    }

    public function test_tags_are_visible_on_event_details_page(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $tags = collect([
            Tag::factory()->create(['name' => 'Workshop']),
            Tag::factory()->create(['name' => 'Technology'])
        ]);
        
        $event = Event::factory()->create(['organiser_id' => $organiser->id]);
        $event->tags()->attach($tags->pluck('id'));

        $response = $this->get("/events/{$event->id}");

        $response->assertStatus(200);
        $response->assertSee('Workshop');
        $response->assertSee('Technology');
    }

    public function test_events_can_be_filtered_by_tag_on_homepage(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $workshopTag = Tag::factory()->create(['name' => 'Workshop']);
        $techTag = Tag::factory()->create(['name' => 'Technology']);

        // Create events with different tags
        $workshopEvent = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Workshop Event',
            'start_time' => now()->addDays(7),
        ]);
        $workshopEvent->tags()->attach($workshopTag);

        $techEvent = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Tech Event',
            'start_time' => now()->addDays(7),
        ]);
        $techEvent->tags()->attach($techTag);

        $mixedEvent = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Mixed Event',
            'start_time' => now()->addDays(7),
        ]);
        $mixedEvent->tags()->attach([$workshopTag->id, $techTag->id]);

        // Test filtering by workshop tag
        $response = $this->get('/?tags[]=' . $workshopTag->id);
        
        $response->assertStatus(200);
        $response->assertSee('Workshop Event');
        $response->assertSee('Mixed Event');
        $response->assertDontSee('Tech Event');
    }

    public function test_ajax_filtering_works_without_page_reload(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $tag = Tag::factory()->create(['name' => 'Workshop']);

        $taggedEvent = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Tagged Event',
            'start_time' => now()->addDays(7),
        ]);
        $taggedEvent->tags()->attach($tag);

        $untaggedEvent = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Untagged Event',
            'start_time' => now()->addDays(7),
        ]);

        // Simulate AJAX request
        $response = $this->get('/events/filter?tags[]=' . $tag->id, [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJson(fn($json) => 
            $json->has('html')
                ->where('html', fn($html) => 
                    str_contains($html, 'Tagged Event') && 
                    !str_contains($html, 'Untagged Event')
                )
        );
    }

    public function test_invalid_tag_ids_are_rejected(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $this->actingAs($organiser);

        $eventData = [
            'title' => 'Test Event',
            'description' => 'Test description',
            'start_time' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'end_time' => now()->addDays(7)->addHours(2)->format('Y-m-d\TH:i'),
            'location' => 'Test Location',
            'capacity' => 50,
            'tags' => [999], // Non-existent tag ID
        ];

        $response = $this->post('/events', $eventData);

        $response->assertSessionHasErrors(['tags.0']);
    }

    public function test_organiser_can_remove_all_tags_from_event(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $tag = Tag::factory()->create();
        
        $event = Event::factory()->create(['organiser_id' => $organiser->id]);
        $event->tags()->attach($tag);

        $this->actingAs($organiser);

        $updateData = [
            'title' => $event->title,
            'description' => $event->description,
            'start_time' => $event->start_time->format('Y-m-d\TH:i'),
            'end_time' => $event->end_time->format('Y-m-d\TH:i'),
            'location' => $event->location,
            'capacity' => $event->capacity,
            // No tags array = remove all tags
        ];

        $response = $this->put("/events/{$event->id}", $updateData);

        $event->refresh();
        $this->assertCount(0, $event->tags);
        
        $response->assertRedirect("/events/{$event->id}");
    }

    public function test_events_can_be_filtered_with_any_match_logic(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $workshopTag = Tag::factory()->create(['name' => 'Workshop']);
        $techTag = Tag::factory()->create(['name' => 'Technology']);
        $designTag = Tag::factory()->create(['name' => 'Design']);

        // Event with only Workshop
        $workshopOnly = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Workshop Only Event',
            'start_time' => now()->addDays(7),
        ]);
        $workshopOnly->tags()->attach($workshopTag);

        // Event with only Technology
        $techOnly = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Tech Only Event',
            'start_time' => now()->addDays(7),
        ]);
        $techOnly->tags()->attach($techTag);

        // Event with both Workshop and Technology
        $both = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Workshop and Tech Event',
            'start_time' => now()->addDays(7),
        ]);
        $both->tags()->attach([$workshopTag->id, $techTag->id]);

        // Event with only Design (unrelated)
        $designOnly = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Design Only Event',
            'start_time' => now()->addDays(7),
        ]);
        $designOnly->tags()->attach($designTag);

        // Test ANY match (OR logic) - should show events with Workshop OR Technology
        $response = $this->get('/?tags[]=' . $workshopTag->id . '&tags[]=' . $techTag->id . '&tag_match=any');
        
        $response->assertStatus(200);
        $response->assertSee('Workshop Only Event');  // Has Workshop
        $response->assertSee('Tech Only Event');      // Has Technology
        $response->assertSee('Workshop and Tech Event'); // Has both
        $response->assertDontSee('Design Only Event'); // Has neither
    }

    public function test_events_can_be_filtered_with_all_match_logic(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $workshopTag = Tag::factory()->create(['name' => 'Workshop']);
        $techTag = Tag::factory()->create(['name' => 'Technology']);
        $designTag = Tag::factory()->create(['name' => 'Design']);

        // Event with only Workshop
        $workshopOnly = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Workshop Only Event',
            'start_time' => now()->addDays(7),
        ]);
        $workshopOnly->tags()->attach($workshopTag);

        // Event with only Technology
        $techOnly = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Tech Only Event',
            'start_time' => now()->addDays(7),
        ]);
        $techOnly->tags()->attach($techTag);

        // Event with both Workshop and Technology
        $both = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Workshop and Tech Event',
            'start_time' => now()->addDays(7),
        ]);
        $both->tags()->attach([$workshopTag->id, $techTag->id]);

        // Event with all three tags
        $allThree = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'All Three Tags Event',
            'start_time' => now()->addDays(7),
        ]);
        $allThree->tags()->attach([$workshopTag->id, $techTag->id, $designTag->id]);

        // Test ALL match (AND logic) - should only show events with BOTH Workshop AND Technology
        $response = $this->get('/?tags[]=' . $workshopTag->id . '&tags[]=' . $techTag->id . '&tag_match=all');
        
        $response->assertStatus(200);
        $response->assertDontSee('Workshop Only Event');  // Missing Technology
        $response->assertDontSee('Tech Only Event');      // Missing Workshop
        $response->assertSee('Workshop and Tech Event');  // Has both ✓
        $response->assertSee('All Three Tags Event');     // Has both (and more) ✓
    }

    public function test_ajax_filtering_respects_and_or_logic(): void
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $workshopTag = Tag::factory()->create(['name' => 'Workshop']);
        $techTag = Tag::factory()->create(['name' => 'Technology']);

        $workshopOnly = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Workshop Only',
            'start_time' => now()->addDays(7),
        ]);
        $workshopOnly->tags()->attach($workshopTag);

        $both = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Both Tags',
            'start_time' => now()->addDays(7),
        ]);
        $both->tags()->attach([$workshopTag->id, $techTag->id]);

        // Test AJAX with ALL logic
        $response = $this->get('/events/filter?tags[]=' . $workshopTag->id . '&tags[]=' . $techTag->id . '&tag_match=all', [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJson(fn($json) => 
            $json->has('html')
                ->where('html', fn($html) => 
                    str_contains($html, 'Both Tags') && 
                    !str_contains($html, 'Workshop Only')
                )
        );
    }
}