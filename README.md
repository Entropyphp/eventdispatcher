# eventdispatcher

[![Latest Stable Version](http://poser.pugx.org/entropyphp/eventdispacher/v)](https://packagist.org/packages/entropyphp/eventdispacher) 
[![Total Downloads](http://poser.pugx.org/entropyphp/eventdispacher/downloads)](https://packagist.org/packages/entropyphp/eventdispacher) 
[![Latest Unstable Version](http://poser.pugx.org/entropyphp/eventdispacher/v/unstable)](https://packagist.org/packages/entropyphp/eventdispacher) 
[![License](http://poser.pugx.org/entropyphp/eventdispacher/license)](https://packagist.org/packages/entropyphp/eventdispacher) 
[![PHP Version Require](http://poser.pugx.org/entropyphp/eventdispacher/require/php)](https://packagist.org/packages/entropyphp/eventdispacher)
[![Coverage Status](https://coveralls.io/repos/github/Entropyphp/eventdispatcher/badge.svg?branch=main)](https://coveralls.io/github/Entropyphp/eventdispatcher?branch=main)
  
Psr14 Event Dispatcher
## Installation

```bash
composer require entropyphp/eventdispatcher
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
 ['eventName'] //default __invoke method with default priority 0
 ['eventName' => ['methodName', ListenerPriority::HIGH]] //methodName with high priority
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
            RequestEvent::NAME => ListenerPriority::HIGH
        ];
    }
}

$dispatcher = new EventDispatcher($callableResolver);
$dispatcher->subscribeListener(MyListener::class);
```