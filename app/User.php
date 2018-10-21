<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * Get the route key name for laravel.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * A user may has threads.
     *
     * @return \Illuminate\Database\Eloquent\Relation\HasMany
     */
    public function threads()
    {
        return $this->hasMany('App\Thread')->latest();
    }

    /**
     * Get all activity for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relation\HasMany
     */
    public function activity()
    {
        return $this->hasMany('App\Activity');
    }

    /**
     * Return the way to the visited thread.
     *
     * @param  Thread $thread
     * @return string
     */
    public function visitedThreadCacheKey($thread)
    {
        return sprintf('users.%s.visits.%s', $this->id, $thread->id);
    }

    /**
     * Save in cache the visited thread.
     *
     * @param  \App\Thread $thread [description]
     */
    public function read($thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            \Carbon\Carbon::now()
        );
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }
}
