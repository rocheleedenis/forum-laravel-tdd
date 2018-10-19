<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class ThreadSubscription extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user associated with the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the thread associated with the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    /**
     * Notify the related user that the thread was updated.
     *
     * @param \App\Reply $reply
     */
    public function notify($reply)
    {
        $this->user->notify(new ThreadWasUpdated($this->thread, $reply));
    }
}
