<?php

namespace Tests\Feature;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FriendsTest extends TestCase {
    use RefreshDatabase;
    public function test_user_can_send_friend_request() {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $anotherUser = User::factory()->create();

        $response = $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
        ]);
        $response->assertStatus(200);


        $friendRequest = Friend::first();
        $this->assertNotNull($friendRequest);
        $this->assertEquals($anotherUser->id, $friendRequest->friend_id);
        $this->assertEquals($user->id, $friendRequest->user_id);

        $response->assertJson([
            'data' => [
                'type' => 'friend-request',
                'friend_request_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => null,
                ]
            ],
            'links' => [
                'self' => url('/users/' . $anotherUser->id),
            ]
        ]);
    }
}
