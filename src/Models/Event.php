<?php

namespace Sacred96\Timeline\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public const INITIATOR_SIDE = 'initiator_side';
    public const SECOND_SIDE = 'second_side';

    protected $table = 'timeline_events';
    protected $fillable = ['history_id', 'participation_id', 'title', 'comment'];
    protected $touches = ['history'];

    public function history(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(History::class, 'history_id', 'id');
    }

    public function participant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Participation::class, 'participation_id', 'id');
    }

    public function createNew(
        History $history,
        Participation $participant,
        string $title,
        string $comment = null
    ) {
        return $history->events()->create([
            'participation_id' => $participant->getKey(),
            'title'            => $title,
            'comment'          => $comment,
        ]);
    }

    public function getAuthorAttribute()
    {
        return $this->participant->eventable;
    }

    public function side(): string
    {
        return $this->participant->eventable->isTimelineInitiator() ? self::INITIATOR_SIDE : self::SECOND_SIDE;
    }

}
