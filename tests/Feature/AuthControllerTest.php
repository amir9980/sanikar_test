<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_a_user()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'newuser',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'حساب شما با موفقیت ایجاد شد!']);

        $this->assertDatabaseHas('users', ['name' => 'newuser']);
    }

    public function test_login_returns_token_for_valid_credentials()
    {
        $user = User::factory()->create([
            'name' => 'testuser',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'name' => 'testuser',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['name', 'role', 'token']);

        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'name' => 'testuser2',
            'password' => bcrypt('correctpassword'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'name' => 'testuser2',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function test_logout_deletes_user_tokens()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $user->createToken('test-token');

        $this->assertCount(1, $user->tokens);

        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'با موفقیت خارج شدید.']);

        $this->assertCount(0, $user->fresh()->tokens);
    }
}
