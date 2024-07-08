<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserImageResource;
use Illuminate\Http\Request;

class UserImageController extends Controller {
    public function store(Request $request) {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'width' => 'required|integer|min:1',
            'height' => 'required|integer|min:1',
            'location' => 'required|string'
        ]);

        $image = $data['image']->store('user-images', 'public');

        $userImage = auth()->user()->images()->create([
            'path' => $image,
            'width' => $data['width'],
            'height' => $data['height'],
            'location' => $data['location']
        ]);

        return new UserImageResource($userImage);
    }
}
