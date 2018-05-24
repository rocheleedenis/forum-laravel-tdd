<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function favorites()
    {
        return $this->morphMany('App\Favorite', 'favorited');
    }

    /**
     * Favorita o reply apenas uma vez.
     *
     * @return type
     */
    public function favorite()
    {
        $attributtes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributtes)->exists()) {
            return $this->favorites()->create($attributtes);
        }
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }
}
