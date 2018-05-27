<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;

class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     *
     * @return void
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

        return view('threads.index', compact('threads'));
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

    /**
     * Show all the threads.
     *
     * @param integer $request
     * @param Thread $thread
     * @return \Iluminate\Http\Response
     */
    public function show($channelId, Thread $thread)
    {
        $replies = $thread->replies()->paginate(15);

        return view('threads.show', compact('thread', 'replies'));
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

        return $threads->get();
    }
}
