<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model {
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['confirmed_at'];

    public static function friendship($user_id) {
        return (new static)
            ->where(function ($query) use ($user_id) {
                return $query
                    ->where('user_id', auth()->user()->id)
                    ->where('friend_id', $user_id);
            })
            ->orWhere(function ($query) use ($user_id) {
                return $query
                    ->where('user_id', $user_id)
                    ->where('friend_id', auth()->user()->id);
            })
            ->first();
    }
}
