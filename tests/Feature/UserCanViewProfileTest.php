<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCanViewProfileTest extends TestCase {
    use RefreshDatabase;
    public function test_user_can_view_user_profiles() {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->get('/api/users/' . $user->id);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'type' => 'users',
                'user_id' => $user->id,
                'attributes' => [
                    'name' => $user->name
                ],
            ],
            'links' => [
                'self' => url('/users/' . $user->id)
            ]
        ]);
    }

    public function test_user_can_fetch_posts_for_a_profile() {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $posts = Post::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->get('/api/users/' . $user->id . '/posts');

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'posts',
                        'post_id' => $posts->last()->id,
                        'attributes' => [
                            'body' => $posts->last()->body,
                            'image' => $posts->last()->image,
                            'posted_at' => $posts->last()->created_at->diffForHumans(),
                            'posted_by' => [
                                'data' => [
                                    'attributes' => [
                                        'name' => $user->name,
                                    ]
                                ]
                            ],
                        ],
                    ],
                    'links' => [
                        'self' => url('/posts/' . $posts->last()->id)
                    ]
                ],
                [
                    'data' => [
                        'type' => 'posts',
                        'post_id' => $posts->first()->id,
                        'attributes' => [
                            'body' => $posts->first()->body,
                            'image' => $posts->first()->image,
                            'posted_at' => $posts->first()->created_at->diffForHumans(),
                            'posted_by' => [
                                'data' => [
                                    'attributes' => [
                                        'name' => $user->name,
                                    ]
                                ]
                            ],
                        ],
                    ],
                    'links' => [
                        'self' => url('/posts/' . $posts->first()->id)
                    ]
                ],
            ],
            'links' => [
                'self' => url('/posts/')
            ]
        ]);
    }

    public function test_user_without_posts_on_profile() {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->get('/api/users/' . $user->id . '/posts');

        $response->assertStatus(200)->assertJson([
            'data' => []
        ]);
    }
}
