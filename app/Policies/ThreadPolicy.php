<?php

namespace App\Policies;

use App\User;
use App\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Authorize the user Rochele to edit all threads.
     *
     * @param type $user
     * @return boolean
     */
    public function before(User $user)
    {
        // if ($user->name === 'Rochele') {
        //     return true;
        // }
    }

    /**
     * Determine whether the user can update the thread.
     *
     * @param  \App\User    $user
     * @param  \App\Thread  $thread
     * @return boolean
     */
    public function update(User $user, Thread $thread)
    {
        return $thread->user_id == $user->id;
    }
}
