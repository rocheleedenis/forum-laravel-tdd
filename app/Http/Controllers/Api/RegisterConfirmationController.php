<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        try {
            $user = User::where('confirmation_token', request('token'))
                ->firstOrFail()
                ->confirm();
        } catch (\Exception $e) {
            return redirect(route('threads'))
                ->with('flash', 'Unknow token.');
        }

        return redirect(route('threads'))
            ->with('flash', 'You account is now confirmed! You may post to the forum.');
    }
}
