<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostToTimelineTest extends TestCase {
    use RefreshDatabase;

    public function test_user_can_post_a_text_post() {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $test_body = 'Testing Body';
        $response = $this->post('/api/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'body' => $test_body,
                ]
            ]
        ]);

        $post = Post::first();

        $this->assertCount(1, $post::all());
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals($test_body, $post->body);
        $response->assertStatus(201)->assertJson([
            'data' => [
                'type' => 'posts',
                'post_id' => $post->id,
                'attributes' => [
                    'posted_by' => [
                        'data' => [
                            'attributes' => [
                                'name' => $user->name
                            ]
                        ]
                    ],
                    'body' => 'Testing Body'
                ]
            ],
            'links' => [
                'self' => url('/posts/' . $post->id)
            ]
        ]);
    }
}
