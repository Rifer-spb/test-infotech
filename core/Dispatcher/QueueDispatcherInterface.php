<?php

namespace Core\Dispatcher;

use yii\queue\JobInterface;

interface QueueDispatcherInterface
{
    public function add(JobInterface $job): void;

    public function release(): void;
}
