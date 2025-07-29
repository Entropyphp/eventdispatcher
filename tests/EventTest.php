<?php

declare(strict_types=1);

namespace Entropy\Tests\Event;

use Entropy\Event\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testEventNameReturnsConstantValue(): void
    {
        $event = $this->getNewEvent();
        $this->assertSame('custom.test.event', $event->eventName());
    }

    public function testEventNameIsString(): void
    {
        $event = $this->getNewEvent();
        $this->assertIsString($event->eventName());
    }

    public function testEventNameIsNotModifiedAfterInstantiation(): void
    {
        $event = $this->getNewEvent();
        $name1 = $event->eventName();
        $name2 = $event->eventName();
        $this->assertSame($name1, $name2);
    }
    protected function getNewEvent(): Event
    {
        return new class extends Event{
            public const NAME = 'custom.test.event';
        };
    }
}
