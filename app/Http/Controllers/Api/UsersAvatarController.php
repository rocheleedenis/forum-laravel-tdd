<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class UsersAvatarController extends Controller
{
    /**
     * Store a new user avatar.
     *
     * @return
     */
    public function store()
    {
        request()->validate([
            'avatar' => ['required', 'image']
        ]);

        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);

        return response([], 204);
    }
}
