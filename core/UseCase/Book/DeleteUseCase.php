<?php

namespace Core\UseCase\Book;

use Core\Entity\Book\Book;
use yii\db\StaleObjectException;

class DeleteUseCase
{
    /**
     * @param int $id
     * @return void
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function execute(int $id): void
    {
        $author = Book::getById($id);
        $author->deleteOrFail();
    }
}
