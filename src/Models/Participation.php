<?php

namespace Sacred96\Timeline\Models;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    protected $table = 'timeline_participation';
    protected $fillable = ['history_id', 'eventable_id', 'eventable_type'];

    public function timeline()
    {
        return $this->belongsTo(History::class, 'history_id', 'id');
    }

    public function eventable()
    {
        return $this->morphTo()->with('participation');
    }
}
