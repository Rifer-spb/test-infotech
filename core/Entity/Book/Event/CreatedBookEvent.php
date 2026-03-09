<?php

namespace Core\Entity\Book\Event;

use Core\Entity\Book\Book;

readonly class CreatedBookEvent
{
    public function __construct(public Book $book) {}
}
