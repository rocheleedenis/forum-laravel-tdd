<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Trending;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;

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
     * @param Channel       $channel
     * @param ThreadFilters $filters
     * @param Trending      $trending
     * @return \Iluminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads'   => $threads,
            'trending'  => $trending->get()
        ]);
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
     * @param integer  $channel
     * @param Thread   $thread
     * @param Trending $trending
     * @return \Iluminate\Http\Response
     */
    public function show($channel, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

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
