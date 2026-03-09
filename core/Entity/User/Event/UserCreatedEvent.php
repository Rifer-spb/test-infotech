<?php

namespace Core\Entity\User\Event;

use Core\Entity\User\User;

readonly class UserCreatedEvent
{
    public function __construct(public User $user)
    {
    }
}
