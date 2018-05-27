<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;

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
            'activities'  => Activity::feed($user)
        ]);
    }

    /**
     * Get the activities from the user.
     *
     * @param User $user
     * @return Activity
     */
    public function getActivitiy(User $user)
    {
        return $user->activity()->latest()->with('subject')->take(50)->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }
}
