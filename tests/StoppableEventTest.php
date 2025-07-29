<?php

declare(strict_types=1);

namespace Entropy\Tests\Event;

use Entropy\Event\StoppableEvent;
use PHPUnit\Framework\TestCase;

class StoppableEventTest extends TestCase
{
    public function testPropagationIsNotStoppedByDefault(): void
    {
        $event = $this->getNewEvent();
        $this->assertFalse($event->isPropagationStopped());
    }

    public function testStopPropagation(): void
    {
        $event = $this->getNewEvent();
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }

    public function testMultipleStopPropagationCalls(): void
    {
        $event = $this->getNewEvent();
        $event->stopPropagation();
        $event->stopPropagation(); // Should not change the state
        $this->assertTrue($event->isPropagationStopped());
    }

    public function testInheritsEventFunctionality(): void
    {
        $event = $this->getNewEvent();
        $this->assertSame('custom.stoppable.event', $event->eventName());
    }

    protected function getNewEvent(): StoppableEvent
    {
        return new class extends StoppableEvent{
            public const NAME = 'custom.stoppable.event';
        };
    }
}
