<?php

namespace Sacred96\Timeline\Services;

use Illuminate\Database\Eloquent\Collection;
use Sacred96\Timeline\Models\History;
use Sacred96\Timeline\Traits\Eventable;

class HistoryService
{
    /**
     * @var \Sacred96\Timeline\Models\History
     */
    private $history;

    public function __construct(History $history)
    {
        $this->history = $history;
    }

    /**
     * @param  History|int  $history
     * @return $this
     */
    public function setHistory($history): HistoryService
    {
        if (is_int($history)) {
            $history = $this->getById($history);
        }

        $this->history = $history;

        return $this;
    }

    /**
     * @param  Eventable[]  $participants
     * @param  string|null  $initialState
     * @return \Illuminate\Database\Eloquent\Model|History
     */
    public function start(array $participants = [], string $initialState = null)
    {
        return $this->history->start($participants, $initialState);
    }

    public function getById(int $id): History
    {
        return $this->history->find($id);
    }

    public function getEvents(string $sortDirection = 'asc'): Collection
    {
        return $this->history->getEvents($sortDirection);
    }

    public function getParticipants(): \Illuminate\Support\Collection
    {
        return $this->history->getParticipants();
    }

    public function getCurrentState(): string
    {
        return $this->history->state;
    }

    public function isFinished(): bool
    {
        return $this->history->finished;
    }

    public function makeFinished(): HistoryService
    {
        $this->history->makeFinished();

        return $this;
    }

    public function switchState(string $state): HistoryService
    {
        $this->history->switchState($state);

        return $this;
    }

    /**
     * @param  Eventable[]  $participants
     * @return \Sacred96\Timeline\Models\History
     */
    public function removeParticipants(array $participants): History
    {
        return $this->history->removeParticipants($participants);
    }

    /**
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete(): ?bool
    {
        return $this->history->delete();
    }

}
