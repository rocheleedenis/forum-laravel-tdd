<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['isSubscribedTo'];

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

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

        static::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);
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
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    /**
     * Add a reply to the thread.
     *
     * @param array $reply
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    /**
     * A thread is assigned a channel.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo('App\Channel', 'channel_id');
    }

    /**
     * Apply all relevant thread filters.
     *
     * @param  Builder $query
     * @param  ThreadFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Subscribe a user to the current thread.
     *
     * @param int|null $userId
     * @return $this
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    /**
     * Unsubscribe a user to the current thread.
     *
     * @param int|null $userId
     * @return $this
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    /**
     * A thread can have many subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relation\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany('App\ThreadSubscription');
    }

    /**
     * Determine if the current user is subscribed to the trhead.
     *
     * @return boolean
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    /**
     * Determine if the thread has been updated since the user last read it.
     *
     * @param \App\User $user
     * @return boolean
     */
    public function hasUpdatedFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    /**
     * Get the route key name for laravel.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Set the proper slug attribute.
     *
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);

        while (static::whereSlug($slug)->exists()) {
            $slug = $slug . '-' . $this->id;
        }

        // var_dump($slug);
        $this->attributes['slug'] = $slug;
    }
}
