<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel, ThreadFilters $filters)
    {
        if ($channel->exists) {
            $threads = $channel->threads();
        } else {
            $threads = Thread::query();
        }

        // if ($username = request('by')) {
        //     $user = \App\User::where('name', $username)->firstOrFail();

        //     $threads->where('user_id', $user->id);
        // }

        $threads = $threads->filter($filters)->get();

        return view('threads.index', compact('threads'));
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title'      => 'required',
            'body'       => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id'    => auth()->id(),
            'channel_id' => request('channel_id'),
            'title'      => request('title'),
            'body'       => request('body')
        ]);

        return redirect($thread->path());
    }

    public function show($channelId, Thread $thread)
    {
        return view('threads.show', compact('thread'));
    }
}
