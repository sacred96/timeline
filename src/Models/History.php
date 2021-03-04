<?php

namespace Sacred96\Timeline\Models;

use Illuminate\Database\Eloquent\Model;
use Sacred96\Timeline\Traits\Eventable;

/**
 * Class History
 *
 * @package Sacred96\Timeline\Models
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class History extends Model
{
    protected $table = 'timeline_histories';
    protected $fillable = ['state', 'finished'];

    public function participants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Event::class, 'history_id');
    }

    public function getParticipants(): \Illuminate\Support\Collection
    {
        return $this->participants()->get()->pluck('eventable');
    }

    public function getEvents(string $sortDirection = 'asc'): \Illuminate\Database\Eloquent\Collection
    {
        return $this->events()->orderBy('timeline_events.id', $sortDirection)->get();
    }

    /**
     * @param  Eventable[]  $participants
     * @param  string|null  $initialState
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function start(array $participants = [], string $initialState = null)
    {
        $history = $this->create(['state' => $initialState, 'finished' => false]);

        if ( ! empty($participants)) {
            $history->addParticipants($participants);
        }

        return $history;
    }

    /**
     * @param  Eventable[]  $participants
     * @return $this
     */
    public function addParticipants(array $participants): History
    {
        foreach ($participants as $participant) {
            $participant->joinHistory($this);
        }

        return $this;
    }

    /**
     * @param  Eventable[]  $participants
     * @return $this
     */
    public function removeParticipants(array $participants): History
    {
        foreach ($participants as $participant) {
            $participant->leaveConversation($this->getKey());
        }

        return $this;
    }

    public function switchState(string $state): History
    {
        $this->update(['state' => $state]);

        return $this;
    }

    public function makeFinished(): History
    {
        $this->update(['finished' => true]);

        return $this;
    }

    public function delete(): ?bool
    {
        if ($this->participants()->count() || $this->events()->count()) {
            throw new \DomainException(trans('timeline::errors.try_delete_not_empty_timeline'));
        }

        return parent::delete();
    }

}
