<?php

namespace Core\Event;

final class DomainEvent
{
    /** @var object[] */
    private static array $events = [];

    public static function add(object $event): void
    {
        self::$events[] = $event;
    }

    public static function release(): array
    {
        $events = self::$events;
        self::$events = [];
        return $events;
    }
}
