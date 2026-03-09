<?php

namespace Core\Dispatcher;

class EventDispatcher implements EventDispatcherInterface
{
    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatch($event): void
    {
        //......
    }
}
