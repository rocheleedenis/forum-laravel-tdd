<?php

namespace App;

trait RecordsActivity
{
    /**
     * Boot the trait.
     *
     * @return type
     */
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return;
        }

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    /**
     * Create a new activity for the current thread.
     *
     * @param string $event
     */
    protected function recordActivity($event)
    {
        $this->activity()->create([
            'type'    => $this->getActivityType($event),
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Define.
     *
     * @return \Illuminate\Database\Eloquent\Relation\MorphMany
     */
    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * Get the type of the activity.
     *
     * @param string $event
     * @return string
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }
}
