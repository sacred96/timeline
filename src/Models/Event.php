<?php

namespace Sacred96\Timeline\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
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

}
