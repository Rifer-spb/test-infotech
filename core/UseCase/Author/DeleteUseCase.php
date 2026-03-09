<?php

namespace Core\UseCase\Author;

use Core\Entity\Author\Author;
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
        $author = Author::getById($id);

        if ($author->hasBooks()) {
            throw new \DomainException('Нельзя удалить автора с имеющимися книгами.');
        }

        $author->deleteOrFail();
    }
}
