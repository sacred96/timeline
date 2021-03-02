<?php

namespace Sacred96\Timeline\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'histories';
    protected $fillable = ['state', 'finished'];

    public function participants()
    {
        return $this->hasMany(Participation::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'history_id');
    }

    public function getParticipants()
    {
        return $this->participants()->get()->pluck('eventable');
    }

    public function getEvents(string $sortDirection = 'asc')
    {
        return $this->events()->orderBy('timeline_events.id', $sortDirection)->get();
    }

}
