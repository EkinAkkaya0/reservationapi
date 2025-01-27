<?php

namespace Tests\Unit;

use App\Models\Seat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SeatControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_block_seats()
    {
        // Create some seats with 'available' status
        $seats = Seat::factory()->count(3)->create([
            'status' => 'available'
        ]);

        // Prepare the seat IDs for the request
        $seatIds = $seats->pluck('id')->toArray();

        // Send the request to block seats
        $response = $this->postJson('/api/seats/block', ['seat_ids' => $seatIds]);

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that all seats are now blocked
        $this->assertDatabaseHas('seats', [
            'id' => $seats[0]->id,
            'status' => 'blocked',
        ]);
        $this->assertDatabaseHas('seats', [
            'id' => $seats[1]->id,
            'status' => 'blocked',
        ]);
        $this->assertDatabaseHas('seats', [
            'id' => $seats[2]->id,
            'status' => 'blocked',
        ]);
    }

    #[Test]
    public function it_returns_error_when_seats_not_found_to_block()
    {
        // Send the request to block seats with invalid IDs
        $response = $this->postJson('/api/seats/block', ['seat_ids' => [9999, 10000]]);

        // Assert that the response status is 404
        $response->assertStatus(404)
                 ->assertJson(['error' => 'No seats found']);
    }

    #[Test]
    public function it_can_release_seats()
    {
        // Create some blocked seats
        $seats = Seat::factory()->count(3)->create([
            'status' => 'blocked'
        ]);

        // Prepare the seat IDs for the request
        $seatIds = $seats->pluck('id')->toArray();

        // Send the request to release seats
        $response = $this->deleteJson('/api/seats/release', ['seat_ids' => $seatIds]);

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that all seats are now available
        $this->assertDatabaseHas('seats', [
            'id' => $seats[0]->id,
            'status' => 'available',
        ]);
        $this->assertDatabaseHas('seats', [
            'id' => $seats[1]->id,
            'status' => 'available',
        ]);
        $this->assertDatabaseHas('seats', [
            'id' => $seats[2]->id,
            'status' => 'available',
        ]);
    }

    #[Test]
    public function it_returns_error_when_no_blocked_seats_to_release()
    {
        // Send the request to release seats with no blocked seats
        $response = $this->deleteJson('/api/seats/release', ['seat_ids' => [9999, 10000]]);

        // Assert that the response status is 404
        $response->assertStatus(404)
                 ->assertJson(['error' => 'No blocked seats found']);
    }
}
