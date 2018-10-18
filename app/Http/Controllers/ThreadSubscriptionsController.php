<?php

namespace App\Http\Controllers;

use App\thread;

class ThreadSubscriptionsController extends Controller
{
    /**
     * Store a newly created thread subscription in storege.
     *
     * @param string $channel
     * @param \App\Thread $thread
     * @return void
     */
    public function store($channel, Thread $thread)
    {
        $thread->subscribe();
    }

    /**
     * Delete a thread subscription in storege.
     *
     * @param string $channel
     * @param \App\Thread $thread
     * @return void
     */
    public function destroy($channel, Thread $thread)
    {
        $thread->unsubscribe();
    }
}
