<?php

namespace Sacred96\Timeline\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'timeline_events';
    protected $fillable = ['history_id', 'participation_id', 'title', 'comment'];
    protected $touches = ['timeline'];

    public function timeline()
    {
        return $this->belongsTo(History::class, 'history_id', 'id');
    }

    public function participant()
    {
        return $this->belongsTo(Participation::class, 'participation_id', 'id');
    }

}
