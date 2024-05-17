<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAuthUserTest extends TestCase {
    use RefreshDatabase;
    public function test_authenticated_user_can_be_fetched() {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user, 'api');


        $response = $this->get('/api/auth-user');
        $response->assertStatus(200)->assertJson([
            'data' => [
                'user_id' => $user->id,
                'attributes' => [
                    'name' => $user->name,
                ]
            ],
            'links' => [
                'self' => url('/users/' . $user->id)
            ]
        ]);
    }

    public function test_unauthenticated_user_cannot_be_fetched() {

        $this->json('GET', '/api/auth-user')->assertStatus(401);

        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->get('/api/auth-user'); //This route is protected with auth:api
    }
}
