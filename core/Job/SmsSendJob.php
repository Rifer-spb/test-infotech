<?php

namespace Core\Job;

use Core\Service\Container;
use Core\Service\Sms\SmsSenderInterface;
use yii\queue\RetryableJobInterface;

class SmsSendJob implements RetryableJobInterface
{
    protected int $phone;
    protected string $message;

    public function __construct(int $phone, string $message)
    {
        if (empty($phone) || empty($message)) {
            throw new \DomainException('Не все параметры указаны.');
        }

        $this->phone = $phone;
        $this->message = $message;
    }

    public static function create(int $chatId, string $message): self
    {
        return Container::get(static::class, [$chatId, $message]);
    }

    public function execute($queue): void
    {
        try {
            $smsSender = Container::get(SmsSenderInterface::class);
            $smsSender->send($this->phone, $this->message);
        } catch (\Throwable $e) {
            \Yii::$app->errorHandler->logException($e);
            throw $e;
        }
    }

    public function getTtr(): int
    {
        return 300;
    }

    public function canRetry($attempt, $error): bool
    {
        return $attempt < 3;
    }
}
