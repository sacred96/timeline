<?php

namespace Sacred96\Timeline\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Sacred96\Timeline\Models\History;
use Sacred96\Timeline\Models\Participation;

/**
 * Trait Eventable
 *
 * @package Sacred96\Timeline\Traits
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Eventable
{
    public function participation(): MorphMany
    {
        return $this->morphMany(Participation::class, 'eventable');
    }

    public function joinHistory(History $timeline): void
    {
        $participation = new Participation([
            'history_id'    => $timeline->getKey(),
            'eventable_id'   => $this->getKey(),
            'eventable_type' => $this->getMorphClass(),
        ]);

        $this->participation()->save($participation);
    }

    public function leaveConversation($timelineID): void
    {
        $this->participation()->where([
            'history_id'    => $timelineID,
            'eventable_id'   => $this->getKey(),
            'eventable_type' => $this->getMorphClass(),
        ])->delete();
    }

}
