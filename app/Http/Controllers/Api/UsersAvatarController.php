<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class UsersAvatarController extends Controller
{
    /**
     * Save a new avatar for the signed user.
     *
     * @return
     */
    public function store()
    {
        $this->validate(request(), [
            'avatar' => ['required', 'image']
        ]);

        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);

        return response([], 204);
    }
}
