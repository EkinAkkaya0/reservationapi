<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    public function test_login_failure()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'Unauthorized']);
    }

    public function test_register_success()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'token', 'user']);
    }

    public function test_register_validation_error()
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure(['errors']);
    }

    public function test_refresh_token_success()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->postJson('/api/auth/refresh', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    public function test_logout_success()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->postJson('/api/auth/logout', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Successfully logged out']);
    }

    public function test_user_details_success()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->getJson('/api/auth/user', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'name', 'email']);
    }
}
