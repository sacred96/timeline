## Timeline
Create a timeline history featuring different models 

## Introduction
This package allows you to add a timeline service to your Laravel ^5.8 application

## Installation
From the command line, run:
```
composer require sacred96/timeline
```

Publish migrations:
```
php artisan vendor:publish --tag=timeline.migrations
```

Run the migrations:
```
php artisan migrate
```

## Usage
You can specify different Models as participants. Optionally, you can specify the model as the timeline initiator by making it the owner of the timeline. For example, you can have `Invoice` and `Companies` models that are participants in the timeline

#### Main idea
You create a timeline history by specifying multiple models as story participants. When there is an initiating model among the participants, it must use the `TimelineInitiator` trait. For example, for the history of article moderation, the article will be the initiator.

After creating the timeline, you add events to the history with an indication of its author. Additionally, you can specify a detailed comment for the event.

History may have actual state. For example, the initiator model expects user action. This can be indicated in the current state of the timeline. When the story ends, you mark the timeline as complete.

#### Adding the ability to participate in timeline

Add the `Sacred96\Timeline\Traits\Eventable` trait to any Model you want to participate in Timeline history.  

#### Indicate that the model is the initiator of the timeline

Add the trait `Sacred96\Timeline\Traits\TimelineInitiator` to indicate that this model is the initiator of the timeline.

This can be useful if you want to highlight different sides of the timeline. Let's say you have an invoice that started the timeline. Here he is the initiator. The rest of the actors, such as the invoiced company, will simply be a participant in the timeline.

#### Creating the timeline history
You can start a timeline history with an array of models as participants. You can also set the current state.

```php
$history = Timeline::create([$invoiceModel, $userModel, ..., $anotherModel], 'Initial state');
```

#### Get timeline history by id
```php
$history = Timeline::history()->getById($id);
```

#### Add a new event to history
```php
Timeline::event('Event text')
        ->from($eventableModel)
        ->to($history)
        ->withComment('Extended comment to event')
        ->push();
```

#### Get event by id
```php
Timeline::events()->getByID($id);
```

#### Get event info
```php
$event->author; // event author

$eventSide = $event->side(); // event side: [initiator_side|second_side]
```

#### Timeline history actual state
```php
$history->switchState('New state text'); // Set timeline actual state

$history->getCurrentState(); // Get timeline actual state
```

#### Additional function of timeline history
```php
Timeline::history($history)->getParticipants(); // get all participants of timeline history
Timeline::history($history)->removeParticipants([$participant1, $participant2]); // delete multiple participants
Timeline::history($history)->getEvents('desc'); // get all events of history with sort
Timeline::history($history)->makeFinished(); // make history as finished
Timeline::history($history)->isFinished(); // check if the story is complete

``` 

## License

Timeline package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
