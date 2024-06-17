<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikesTest extends TestCase {
    use RefreshDatabase;
    public function test_user_can_like_a_post() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $post = Post::factory()->create();

        $response = $this->post('/api/posts/' . $post->id . '/like');
        $response->assertStatus(200);

        $this->assertCount(1, $user->likedPosts);
        $this->assertCount(1, $post->likes);
        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'likes',
                        'like_id' => 1,
                        'attributes' => []
                    ],
                    'links' => [
                        'self' => url('/posts/1')
                    ]
                ]
            ],
            'links' => [
                'self' => url('/posts/')
            ]
        ]);
    }

    public function test_post_are_returned_with_likes() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $post = Post::factory()->create(['user_id' => $user->id]);
        $this->post('/api/posts/' . $post->id . '/like')->assertStatus(200);


        $response = $this->get('/api/posts/');
        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'posts',
                        'attributes' => [
                            'likes' => [
                                'data' => [
                                    [
                                        'data' => [
                                            'type' => 'likes',
                                            'like_id' => 1,
                                            'attributes' => []
                                        ]
                                    ]
                                ],
                                'like_count' => 1,
                                'user_likes_post' => true,
                            ],
                        ]
                    ]
                ]
            ]
        ]);
    }
}
