<?php

namespace Sacred96\Timeline\Models;

use Illuminate\Database\Eloquent\Model;
use Sacred96\Timeline\Traits\Eventable;

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
            if ( ! in_array(Eventable::class, class_uses_recursive($participant))) {
                throw new \DomainException(trans('timeline::errors.partisipant_not_eventable'));
            }

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
            $participant->leaveHistory($this->getKey());
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

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $initiator
     * @return \Sacred96\Timeline\Models\Participation|object|null
     */
    public function participantFromEventInitiator(Model $initiator)
    {
        return $this->participants()->where([
            'history_id'     => $this->getKey(),
            'eventable_id'   => $initiator->getKey(),
            'eventable_type' => $initiator->getMorphClass(),
        ])->first();
    }

}
