# pg-eventdispatcher

[![Latest Stable Version](https://poser.pugx.org/willy68/pg-eventdispatcher/v/stable)](https://packagist.org/packages/willy68/pg-eventdispatcher)
[![Total Downloads](https://poser.pugx.org/willy68/pg-eventdispatcher/downloads)](https://packagist.org/packages/willy68/pg-eventdispatcher)
[![License](https://poser.pugx.org/willy68/pg-eventdispatcher/license)](https://packagist.org/packages/willy68/pg-eventdispatcher).
[![PHP Version Require](https://poser.pugx.org/willy68/pg-eventdispatcher/require/php)](https://packagist.org/packages/willy68/pg-eventdispatcher)

Psr14 Event Dispatcher
## Installation

```bash
composer require willy68/pg-eventdispatcher
```

## Subscribe to this dispatcher

 The array keys are event names and the value can be:

 The method name to call (priority defaults to 0)
 The priority (default __invoke class method)
 The eventName (default __invoke class method) (priority defaults to 0)
 An array composed of the method name to call and the priority

 For instance:
```php
 ['eventName' => 'methodName'] //default priority 0
 ['eventName' => ListenerPriority::HIGH] //default __invoke method
 ['eventName' => ['methodName', ListenerPriority::HIGH]] //methodName with high priority
 ['eventName'] //default __invoke method with default priority 0
```
## Example

```php
use Pg\Event\EventSubscriberInterface;
use League\Event\ListenerPriority;
use Pg\Event\EventDispatcher;

class MyListener implements EventSubscriberInterface
{
    public function __invoke(RequestEvent $event)
    {
        // Handle the event
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ListenerPriority::HIGH
        ];
    }
}

$dispatcher = new EventDispatcher($callableResolver);
$dispatcher->subscribeListener(MyListener::class);
```