<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('api.v1.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'result' => [
                    'token',
                ],
            ]);
    }

    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.v1.user'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'result' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson(route('api.v1.logout'));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_protected_routes(): void
    {
        $this->getJson(route('api.v1.user'))->assertStatus(401);
        $this->postJson(route('api.v1.logout'))->assertStatus(401);
    }
}
