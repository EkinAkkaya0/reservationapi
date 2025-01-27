<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method (get all events).
     *
     * @return void
     */
    public function test_index()
    {
        // Arrange: Create venues and events
        $venue = Venue::factory()->create();
        $event = Event::factory()->create([
            'venue_id' => $venue->id,
        ]);

        // Act: Send GET request to fetch all events
        $response = $this->getJson('/api/events');

        // Assert: The response should be OK and contain the event data
        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonStructure([ '*' => ['id', 'name', 'description', 'venue', 'start_date', 'end_date', 'status'] ]);
    }

    /**
     * Test the show method (get specific event).
     *
     * @return void
     */
    public function test_show()
    {
        // Arrange: Create a venue and event
        $venue = Venue::factory()->create();
        $event = Event::factory()->create([
            'venue_id' => $venue->id,
        ]);

        // Act: Send GET request to fetch a specific event
        $response = $this->getJson("/api/events/{$event->id}");

        // Assert: The response should return the event data
        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $event->id, 'name' => $event->name]);
    }

    /**
     * Test the store method (create a new event).
     *
     * @return void
     */
    public function test_store()
    {
        // Arrange: Create a user with admin role
        $user = User::factory()->create(['role' => 'admin']);
        $venue = Venue::factory()->create();
        $eventData = [
            'name' => 'New Event',
            'description' => 'Event description',
            'venue_id' => $venue->id,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(2),
            'status' => 'active',
        ];

        // Act: Authenticate the user and send POST request to create an event
        $response = $this->actingAs($user)->postJson('/api/events', $eventData);

        // Assert: The event should be created successfully
        $response->assertStatus(201)
                 ->assertJson(['message' => 'Event created successfully']);
    }

    /**
     * Test the update method (update an existing event).
     *
     * @return void
     */
    public function test_update()
    {
        // Arrange: Create a user with admin role and an event
        $user = User::factory()->create(['role' => 'admin']);
        $venue = Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);

        $updatedEventData = [
            'name' => 'Updated Event Name',
            'description' => 'Updated description',
            'venue_id' => $venue->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(3),
            'status' => 'inactive',
        ];

        // Act: Authenticate user and send PUT request to update the event
        $response = $this->actingAs($user)->putJson("/api/events/{$event->id}", $updatedEventData);

        // Assert: The event should be updated successfully
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Event updated successfully']);
    }

    /**
     * Test the destroy method (delete an event).
     *
     * @return void
     */
    public function test_destroy()
    {
        // Arrange: Create a user with admin role and an event
        $user = User::factory()->create(['role' => 'admin']);
        $venue = Venue::factory()->create();
        $event = Event::factory()->create(['venue_id' => $venue->id]);

        // Act: Authenticate the user and send DELETE request to delete the event
        $response = $this->actingAs($user)->deleteJson("/api/events/{$event->id}");

        // Assert: The event should be deleted successfully
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Event deleted successfully']);
    }
}
