<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Description
     *
     * @return lluminate\Database\Eloquent\Relation\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }
}
