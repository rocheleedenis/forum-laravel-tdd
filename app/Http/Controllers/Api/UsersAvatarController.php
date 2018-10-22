<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class UsersAvatarController extends Controller
{
    public function store()
    {
        $this->validate(request(), [
            'avatar' => ['required', 'image']
        ]);
    }
}
