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

    public function test_only_valid_users_can_be_requested() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        //$anotherUser = User::factory()->create();

        $response = $this->post('/api/friend-request', [
            'friend_id' => 404, //id that not exists
        ]);
        $response->assertStatus(404);


        $friendRequest = Friend::first();
        $this->assertNull($friendRequest);

        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'User Not Found',
                'detail' => 'Unable to locate the user with the given information.'
            ]
        ]);
    }

    public function test_friend_request_can_be_accepted() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $anotherUser = User::factory()->create();

        $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
        ])->assertStatus(200);

        $response = $this->actingAs($anotherUser, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user->id,
                'status' => 1
            ])->assertStatus(200);

        $friendRequest = Friend::first();
        $this->assertNotNull($friendRequest->confirmed_at);
        $this->assertEquals(now()->startOfSecond(), $friendRequest->confirmed_at);
        $this->assertEquals(1, $friendRequest->status);
        $response->assertJson([
            'data' => [
                'type' => 'friend-request',
                'friend_request_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => $friendRequest->confirmed_at->diffForHumans(),
                ]
            ],
            'links' => [
                'self' => url('/users/' . $anotherUser->id),
            ]
        ]);
    }

    public function test_only_valid_friend_request_can_be_accepted() {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => 404, //id that not exists
                'status' => 1
            ])->assertStatus(404);

        $friendRequest = Friend::first();
        $this->assertNull($friendRequest);

        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Request Not Found',
                'detail' => 'Unable to locate the friend request with the given information.'
            ]
        ]);
    }

    public function test_only_the_recipient_can_accept_a_friend_request() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $anotherUser = User::factory()->create();

        $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
        ])->assertStatus(200);

        $thirdUser = User::factory()->create();

        $response = $this->actingAs($thirdUser, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user->id,
                'status' => 1
            ])->assertStatus(404);

        $friendRequest = Friend::first();
        $this->assertNull($friendRequest->confirmed_at);
        $this->assertEquals(0, $friendRequest->status);
        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Request Not Found',
                'detail' => 'Unable to locate the friend request with the given information.'
            ]
        ]);
    }
}
