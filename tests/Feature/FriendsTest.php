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

    public function test_friend_id_is_required_to_send_a_friend_request() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->post('/api/friend-request', [
            'friend_id' => '',
        ])->assertStatus(422);

        $this->assertArrayHasKey('friend_id', $response->json()['errors']['meta']);
    }

    public function test_user_id_and_status_is_required_for_friend_request_response() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->actingAs($user, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => '',
                'status' => ''
            ])->assertStatus(422);

        $responseJson = $response->json();

        $this->assertArrayHasKey('user_id', $responseJson['errors']['meta']);
        $this->assertArrayHasKey('status', $responseJson['errors']['meta']);
    }

    public function test_friendship_is_retrieved_when_fetching_the_profile() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $anotherUser = User::factory()->create();

        $friendRequest = Friend::create([
            'user_id' => $user->id,
            'friend_id' => $anotherUser->id,
            'confirmed_at' => now()->subday(),
            'status' => 1
        ]);

        $this->get('/api/users/' . $anotherUser->id)->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'users',
                    'user_id' => $anotherUser->id,
                    'attributes' => [
                        'friendship' => [
                            'data' => [
                                'friend_request_id' => $friendRequest->id,
                                'attributes' => [
                                    'confirmed_at' => '1 day ago'
                                ]
                            ]
                        ]
                    ],
                ],
                'links' => [
                    'self' => url('/users/' . $anotherUser->id)
                ]
            ]);
    }

    public function test_inverse_friendship_is_retrieved_when_fetching_the_profile() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $anotherUser = User::factory()->create();

        $friendRequest = Friend::create([
            'user_id' => $anotherUser->id,
            'friend_id' => $user->id,
            'confirmed_at' => now()->subday(),
            'status' => 1
        ]);

        $this->get('/api/users/' . $anotherUser->id)->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'users',
                    'user_id' => $anotherUser->id,
                    'attributes' => [
                        'friendship' => [
                            'data' => [
                                'friend_request_id' => $friendRequest->id,
                                'attributes' => [
                                    'confirmed_at' => '1 day ago'
                                ]
                            ]
                        ]
                    ],
                ],
                'links' => [
                    'self' => url('/users/' . $anotherUser->id)
                ]
            ]);
    }
}
