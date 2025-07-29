<?php

declare(strict_types=1);

namespace Entropy\Tests\Event;

use Entropy\Event\EventDispatcher;
use Entropy\Event\EventSubscriberInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Invoker\CallableResolver;
use League\Event\ListenerPriority;
use Invoker\Exception\NotCallableException;
use ReflectionException;

class EventDispatcherTest extends TestCase
{
    private EventDispatcher $dispatcher;
    private CallableResolver|MockObject $callableResolver;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->callableResolver = $this->createMock(CallableResolver::class);
        $this->dispatcher = new EventDispatcher($this->callableResolver);
    }


    /**
     * @throws NotCallableException
     * @throws ReflectionException
     */
    public function testAddSubscriberWithMethodName(): void
    {
        $subscriber = new class implements EventSubscriberInterface {
            public static function getSubscribedEvents(): array
            {
                return ['test.event' => 'handleEvent'];
            }

            public function handleEvent($event): void
            {
            }
        };

        $this->callableResolver->expects($this->once())
            ->method('resolve')
            ->with([$subscriber, 'handleEvent'])
            ->willReturn(fn($event) => $subscriber->handleEvent($event));

        $this->dispatcher->addSubscriber($subscriber);

        // Verify the subscriber was added (we can't easily test the internal state of the parent class)
        $this->assertInstanceOf(EventDispatcher::class, $this->dispatcher);
    }

    public function testAddSubscriberWithPriority(): void
    {
        $subscriber = new class implements EventSubscriberInterface {
            public static function getSubscribedEvents(): array
            {
                return ['test.event' => ListenerPriority::HIGH];
            }

            public function __invoke($event): void
            {
            }
        };

        $this->callableResolver->expects($this->once())
            ->method('resolve')
            ->with($subscriber)
            ->willReturn(fn($event) => $subscriber($event));

        $this->dispatcher->addSubscriber($subscriber);
        $this->assertInstanceOf(EventDispatcher::class, $this->dispatcher);
    }

    public function testAddSubscriberWithEventNameOnly(): void
    {
        $subscriber = new class implements EventSubscriberInterface {
            public static function getSubscribedEvents(): array
            {
                return ['test.event'];
            }

            public function __invoke($event): void
            {
            }
        };

        $this->callableResolver->expects($this->once())
            ->method('resolve')
            ->with($subscriber)
            ->willReturn(fn($event) => $subscriber($event));

        $this->dispatcher->addSubscriber($subscriber);
        $this->assertInstanceOf(EventDispatcher::class, $this->dispatcher);
    }

    public function testAddSubscriberWithMethodAndPriority(): void
    {
        $subscriber = new class implements EventSubscriberInterface {
            public static function getSubscribedEvents(): array
            {
                return ['test.event' => ['handleEvent', ListenerPriority::HIGH]];
            }

            public function handleEvent($event): void
            {
            }
        };

        $this->callableResolver->expects($this->once())
            ->method('resolve')
            ->with([$subscriber, 'handleEvent'])
            ->willReturn(fn($event) => $subscriber->handleEvent($event));

        $this->dispatcher->addSubscriber($subscriber);
        $this->assertInstanceOf(EventDispatcher::class, $this->dispatcher);
    }

    public function testAddListenersWithPriority(): void
    {
        $listener = new class {
            public function __invoke($event): void
            {
            }
        };

        $this->callableResolver->expects($this->once())
            ->method('resolve')
            ->with('ListenerClass')
            ->willReturn(fn($event) => $listener($event));

        $this->dispatcher->addListeners([
            'ListenerClass' => ['test.event', ListenerPriority::HIGH]
        ]);

        $this->assertInstanceOf(EventDispatcher::class, $this->dispatcher);
    }

    /**
     * @throws NotCallableException
     * @throws ReflectionException
     */
    public function testAddListenersWithDefaultPriority(): void
    {
        $listener = new class {
            public function __invoke($event): void
            {
            }
        };

        $this->callableResolver->expects($this->once())
            ->method('resolve')
            ->with('ListenerClass')
            ->willReturn(fn($event) => $listener($event));

        $this->dispatcher->addListeners([
            'ListenerClass' => 'test.event'
        ]);

        $this->assertInstanceOf(EventDispatcher::class, $this->dispatcher);
    }

    /**
     * @throws NotCallableException
     * @throws ReflectionException
     */
    public function testAddListenersWithMethodName(): void
    {
        $listener = new class {
            public function handle($event): void
            {
            }
        };

        $this->callableResolver->expects($this->once())
            ->method('resolve')
            ->with('ListenerClass::handle')
            ->willReturn(fn($event) => $listener->handle($event));

        $this->dispatcher->addListeners([
            'ListenerClass::handle' => 'test.event'
        ]);

        $this->assertInstanceOf(EventDispatcher::class, $this->dispatcher);
    }

    /**
     * @throws ReflectionException
     */
    public function testAddListenersWithNotCallableThrowsException(): void
    {
        $this->callableResolver->expects($this->once())
            ->method('resolve')
            ->with('NonExistentClass')
            ->willThrowException(new NotCallableException('Not callable'));

        $this->expectException(NotCallableException::class);
        $this->dispatcher->addListeners([
            'NonExistentClass' => 'test.event'
        ]);
    }
}
