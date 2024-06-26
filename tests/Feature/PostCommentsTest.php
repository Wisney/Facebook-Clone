<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostCommentsTest extends TestCase {
    use RefreshDatabase;
    public function test_user_can_comment_a_post() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $post = Post::factory()->create(['id' => 123]);

        $response = $this->post('/api/posts/' . $post->id . '/comment', [
            'body' => 'This is a comment.'
        ]);
        $response->assertStatus(200);

        $comment = Comment::first();

        $this->assertCount(1, Comment::all());
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);
        $this->assertEquals('This is a comment.', $comment->body);
        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'comments',
                        'comment_id' => 1,
                        'attributes' => [
                            'body' => 'This is a comment.',
                            'commented_at' => $comment->created_at->diffForHumans(),
                            'commented_by' => [
                                'data' => [
                                    'user_id' => $user->id,
                                    'attributes' => [
                                        'name' => $user->name,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'links' => [
                        'self' => url('/posts/123')
                    ]
                ]
            ],
            'links' => [
                'self' => url('/posts')
            ]
        ]);
    }

    public function test_body_is_required_to_leave_a_comment_on_post() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $post = Post::factory()->create(['id' => 123]);

        $response = $this->post('/api/posts/' . $post->id . '/comment', [
            'body' => ''
        ]);
        $response->assertStatus(422);

        $responseJson = $response->json();
        $this->assertArrayHasKey('body', $responseJson['errors']['meta']);
    }

    public function test_post_must_returned_with_comments() {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $post = Post::factory()->create(['id' => 123, 'user_id' => $user->id]);

        $response = $this->post('/api/posts/' . $post->id . '/comment', [
            'body' => 'This is a comment.'
        ]);
        $response->assertStatus(200);

        $comment = Comment::first();

        $response = $this->get('/api/posts');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'posts',
                        'post_id' => 123,
                        'attributes' => [
                            'body' => $post->body,
                            'comments' => [
                                'data' => [
                                    [
                                        'data' => [
                                            'type' => 'comments',
                                            'comment_id' => 1,
                                            'attributes' => [
                                                'body' => 'This is a comment.',
                                                'commented_at' => $comment->created_at->diffForHumans(),
                                                'commented_by' => [
                                                    'data' => [
                                                        'user_id' => $user->id,
                                                        'attributes' => [
                                                            'name' => $user->name,
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'links' => [
                                            'self' => url('/posts/123')
                                        ]
                                    ]
                                ],
                                'comment_count' => 1,
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}
