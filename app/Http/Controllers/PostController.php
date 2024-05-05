<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller {

    public function index(Request $request) {
        return new PostCollection($request->user()->posts);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'data.attributes.body' => ''
        ]);

        $post = $request->user()->posts()->create($data['data']['attributes']);

        return new PostResource($post);
    }
}
