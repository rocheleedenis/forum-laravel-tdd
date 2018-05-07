<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function path()
    {
        return '/threads/' . $this->id;
    }
}
