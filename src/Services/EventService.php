<?php

namespace Sacred96\Timeline\Services;

use Illuminate\Database\Eloquent\Model;
use Sacred96\Timeline\Models\Event;
use Sacred96\Timeline\Models\History;

class EventService
{
    /**
     * @var \Sacred96\Timeline\Models\Event
     */
    private $event;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var \Sacred96\Timeline\Models\History
     */
    private $history;
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $eventInitiator;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function setEvent($event): EventService
    {
        if ($event instanceof Event) {
            $this->event = $event;
        } else {
            $this->title = $event;
        }

        return $this;
    }

    public function withComment(string $comment = null): EventService
    {
        if ($comment) {
            $this->comment = nl2br($comment);
        }

        return $this;
    }

    public function from(Model $initiator): EventService
    {
        $this->eventInitiator = $initiator;

        return $this;
    }

    public function to(History $history): EventService
    {
        $this->history = $history;

        return $this;
    }

    public function getByID(int $id): Event
    {
        return $this->event->find($id);
    }

    public function push(): Model
    {
        if ( ! $this->eventInitiator) {
            throw new \RuntimeException(trans('timeline::errors.initiator_not_set'));
        }

        if ( ! $this->title) {
            throw new \RuntimeException(trans('timeline::errors.event_title_not_set'));
        }

        if ( ! $participant = $this->history->participantFromEventInitiator($this->eventInitiator)) {
            throw new \RuntimeException(trans('timeline::errors.partisipant_not_find'));
        }

        return $this->event->createNew(
            $this->history,
            $participant,
            $this->title,
            $this->comment
        );
    }

}
