<?php

namespace Core\Entity;

class NotFoundException extends \DomainException
{
    public function __construct(string $message = 'Не найдено')
    {
        parent::__construct($message, 404);
    }
}
