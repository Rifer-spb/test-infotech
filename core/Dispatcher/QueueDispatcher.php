<?php

namespace Core\Dispatcher;

use Yii;
use yii\queue\JobInterface;

class QueueDispatcher implements QueueDispatcherInterface
{
    private $jobs = [];

    public function add(JobInterface $job): void
    {
        $this->jobs[] = $job;
    }

    public function clean(): void
    {
        $this->jobs = [];
    }

    public function release(): void
    {
        foreach ($this->jobs as $job) {
            Yii::$app->queue->push($job);
        }

        $this->clean();
    }
}
