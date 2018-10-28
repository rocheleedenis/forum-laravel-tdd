<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;
use Illuminate\Support\Facades\Redis;

class ThreadsController extends Controller
{
    /**
     * Create a new ThreadsController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the threads.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Iluminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        $trending = array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));

        return view('threads.index', compact('threads', 'trending'));
    }

    /**
     * Show the form for creating a new thread.
     *
     * @return \Iluminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created thread in storege.
     *
     * @param Request $request
     * @return \Iluminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|spamfree',
            'body'       => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id'    => auth()->id(),
            'channel_id' => request('channel_id'),
            'title'      => request('title'),
            'body'       => request('body')
        ]);

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified thread.
     *
     * @param integer $channel
     * @param Thread $thread
     * @return \Iluminate\Http\Response
     */
    public function show($channel, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        Redis::zincrby('trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path'  => $thread->path()
        ]));

        return view('threads.show', compact('thread'));
    }

    /**
     * Delete the thread and their replies.
     *
     * @param string $channel
     * @param Thread $thread
     * @return \Iluminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

    /**
     * Fetch all relevant threads.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    public function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(5);
    }
}
