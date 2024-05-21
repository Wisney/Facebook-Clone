<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;

class FriendRequestController extends Controller {
    public function store(Request $request) {
        $data = $request->validate([
            'friend_id' => ''
        ]);

        User::find($data['friend_id'])->friends()->attach(auth()->user());

        return new FriendResource(
            Friend::where('user_id', auth()->user()->id)
                ->where('friend_id', $data['friend_id'])->first()
        );
    }
}
