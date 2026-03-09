<?php

namespace Core\UseCase\Author;

use Core\Entity\Author\Author;
use Core\Form\Author\AuthorForm;
use yii\db\Exception;

class CreateUseCase
{
    /**
     * @throws Exception
     */
    public function execute(AuthorForm $form): Author
    {
        return Author::create($form->name);
    }
}
