<?php

namespace App\Http\Controllers;

use App\User;

class ProfilesController extends Controller
{
    /**
     * Show the profile the user.
     *
     * @param User $user
     * @return \Iluminate\Http\Response
     */
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'threads'     => $user->threads()->paginate(20)
        ]);
    }
}
