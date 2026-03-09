<?php

namespace Core\Service;

use Yii;

class Container
{
    public static function get(string $class, array $params = []): mixed
    {
        return Yii::$container->get($class, $params);
    }
}
