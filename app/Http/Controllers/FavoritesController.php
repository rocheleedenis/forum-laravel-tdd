<?php

namespace App\Http\Controllers;

use App\Reply;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Favorita algum thread ou reply.
     *
     * @param Reply $reply
     * @return
     */
    public function store(Reply $reply)
    {
        return $reply->favorite();
    }
}
