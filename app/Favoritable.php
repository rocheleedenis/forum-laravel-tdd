<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
    /**
     * A reply can be favorited.
     *
     * @return \Illuminate\Database\Eloquent\Relation\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany('App\Favorite', 'favorited');
    }

    /**
     * Favorite the current reply.
     *
     * @return Model
     */
    public function favorite()
    {
        $attributtes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributtes)->exists()) {
            return $this->favorites()->create($attributtes);
        }
    }

    /**
     * Determine if the current reply has been favorited.
     *
     * @return type
     */
    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * Get the number of favorites of the current reply.
     *
     * @return integer
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
