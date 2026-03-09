<?php

namespace Core\Listener\Book;

use Core\Entity\Book\Book;
use Core\Dispatcher\QueueDispatcherInterface;
use Core\Entity\Author\AuthorSubscribe;
use Core\Entity\Book\Event\CreatedBookEvent;
use Core\Job\SmsSendJob;

class CreatedBookListener
{
    public function __construct(
        private QueueDispatcherInterface $queueDispatcher,
    )
    {
    }

    public function execute(CreatedBookEvent $event): void
    {
        $book = $event->book;
        $this->checkSubscribes($book);
    }

    private function checkSubscribes(Book $book): void
    {
        $authorIds = $book->getAuthorIds();
        $subscribes = AuthorSubscribe::getAllByAuthorIds($authorIds);

        foreach ($subscribes as $subscribe) {
            $phone = $subscribe->phone;
            $message = "У автора {$subscribe->author->name} поступила новая книга {$book->title}";

            $job = new SmsSendJob($phone, $message);
            $this->queueDispatcher->add($job);
        }
    }
}
