<?php

namespace Sacred96\Timeline;

use Sacred96\Timeline\Models\Event;
use Sacred96\Timeline\Models\History;
use Sacred96\Timeline\Services\EventService;
use Sacred96\Timeline\Services\HistoryService;
use Sacred96\Timeline\Traits\Eventable;

class Timeline
{
    /**
     * @var \Sacred96\Timeline\Services\EventService
     */
    private $eventService;

    /**
     * @var \Sacred96\Timeline\Services\HistoryService
     */
    private $historyService;

    public function __construct(EventService $eventService, HistoryService $historyService)
    {
        $this->eventService = $eventService;
        $this->historyService = $historyService;
    }

    /**
     * Create Timeline
     *
     * @param  Eventable[]  $participants
     * @param  string|null  $initialState
     * @return \Illuminate\Database\Eloquent\Model|History
     */
    public function create(array $participants = [], string $initialState = null)
    {
        $history = $this->historyService->start($participants, $initialState);

        $this->set($history);

        return $history;
    }

    /**
     * Set Timeline
     *
     * @param  History  $history
     * @return HistoryService
     */
    public function set(History $history): HistoryService
    {
        return $this->historyService->setHistory($history);
    }

    /**
     * Set event message
     *
     * @param  string|Event  $event
     *
     * @return EventService
     */
    public function event($event): EventService
    {
        return $this->eventService->setEvent($event);
    }

    /**
     * Get EventService
     *
     * @return EventService
     */
    public function events(): EventService
    {
        return $this->eventService;
    }

    /**
     * Get HistoryService
     *
     * @param  History|null  $history
     *
     * @return HistoryService
     */
    public function history(History $history = null): HistoryService
    {
        if ($history) {
            $this->set($history);
        }

        return $this->historyService;
    }

}
