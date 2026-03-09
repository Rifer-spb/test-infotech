<?php

namespace Core\Service\Sms;

interface SmsSenderInterface
{
    public function send(int $phone, string $message) : void;
}
