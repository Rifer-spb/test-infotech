<?php

namespace Core\UseCase\Author;

use Core\Entity\Author\Author;
use Core\Form\Author\AuthorForm;
use yii\db\Exception;

class UpdateUseCase
{
    /**
     * @param int $id
     * @param AuthorForm $form
     * @return void
     * @throws Exception
     */
    public function execute(int $id, AuthorForm $form): void
    {
        $author = Author::getById($id);
        $author->name = $form->name;
        $author->saveOrFail();
    }
}
