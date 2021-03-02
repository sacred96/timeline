<?php

namespace Sacred96\Timeline\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Eventable
{
    public function participation(): MorphMany
    {
        return $this->morphMany(Participation::class, 'eventable');
    }

}
