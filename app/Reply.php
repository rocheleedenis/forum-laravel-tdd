<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['owner', 'favorites'];

    /**
     * Make visible the attribute on to instance the reply.
     *
     * @var array
     */
    protected $appends = ['favoritesCount', 'isFavorited'];

    /**
     * A reply has a owner.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * A reply belongs to a thread.
     *
     * @return \Illuminate\Database\Eloquent\Relation\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    /**
     * Get a string path for the thread.
     *
     * @return string
     */
    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }
}
