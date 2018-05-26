<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
    * @var array
    */
    protected $with = ['channel'];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::addGlobalScope('creator', function ($builder) {
            $builder->withCount('creator');
        });
    }

    /**
     * A thread may has replies.
     *
     * @return \Illuminate\Database\Eloquent\Relation\HasMany
     */
    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    /**
     * A thread has a creator.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get a string path for the thread.
     *
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * Add a reply to the thread.
     *
     * @param array $reply
     */
    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    /**
     * A thread is assogned a channel.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo('App\Channel', 'channel_id');
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
