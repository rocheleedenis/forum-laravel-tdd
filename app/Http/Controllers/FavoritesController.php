<?php

namespace App\Http\Controllers;

use App\Reply;

class FavoritesController extends Controller
{
    /**
     * Create a new controller instance
     * @return type
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new favorite in the database.
     *
     * @param Reply $reply
     * @return \Iluminate\Database\Eloquent\Model
     */
    public function store(Reply $reply)
    {
        $reply->favorite();

        return back();
    }

    /**
     * Unfavorite an object.
     *
     * @param Reply $reply
     * @return void
     */
    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
    }
}
