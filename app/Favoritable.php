<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

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
     * Fetch the favorited status as a property.
     *
     * @return boolean
     */
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
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

    /**
     * Unfavorite the current reply.
     */
    public function unfavorite()
    {
        $attributtes = ['user_id' => auth()->id()];

        $this->favorites()->where($attributtes)->get()->each->delete();
    }
}
