<?php

namespace App\Models\Traits;

use App\Models\Event;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Eventable
{
    /**
     * @return MorphOne
     */
    public function event(): MorphOne
    {
        return $this->morphOne(Event::class, 'eventable');
    }
}




