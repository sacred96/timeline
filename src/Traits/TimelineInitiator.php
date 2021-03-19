<?php

namespace Sacred96\Timeline\Traits;

use Sacred96\Timeline\Models\History;

/**
 * A timeline can have an event chain initiator.
 * For example, an invoice can be created, transferred as payment and paid.
 * The invoce is the initiator of the timeline.
 * Timeline describes the history of interaction with this initiator.
 *
 * An initiator can have only one timeline. Because there cannot be two
 * payment histories for one invoice. But a company paying an invoice can have
 * many invoices, so there are many payment timelines (histories).
 *
 * This trait was created specifically for initiators to be able
 * to receive an object with their timeline.
 *
 * @package App\Services\Timeline\Traits
 * @property-read \Sacred96\Timeline\Models\History $timeline
 */
trait TimelineInitiator
{
    public function timeline()
    {
        if ($partisipation = $this->participation()->first()) {
            return $partisipation->belongsTo(History::class, 'history_id', 'id');
        }

        return null;
    }

}
