<?php

namespace app\bootstrap;

use Core\Dispatcher\QueueDispatcher;
use Core\Dispatcher\QueueDispatcherInterface;
use yii\base\BootstrapInterface;
use Core\Dispatcher\EventDispatcher;
use Core\Dispatcher\EventDispatcherInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app) : void
    {
        $container = \Yii::$container;
        $container->setSingleton(EventDispatcherInterface::class, EventDispatcher::class);
        $container->setSingleton(QueueDispatcherInterface::class, QueueDispatcher::class);
    }
}
