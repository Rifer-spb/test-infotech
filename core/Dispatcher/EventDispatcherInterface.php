<?php

namespace Core\Dispatcher;

interface EventDispatcherInterface
{
    public function dispatchAll(array $events): void;

    public function dispatch($event): void;
}
