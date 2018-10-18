<?php

namespace App\Http\Controllers;

use App\thread;

class ThreadSubscriptionsController extends Controller
{
    /**
     * Store a newly created thread subscription in storege.
     *
     * @param Request $request
     * @return \Iluminate\Http\Response
     */
    public function store($channel, Thread $thread)
    {
        $thread->subscribe();
    }
}
