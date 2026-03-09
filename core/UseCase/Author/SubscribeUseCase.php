<?php

namespace Core\UseCase\Author;

use Core\Entity\Author\Author;
use Core\Entity\Author\AuthorSubscribe;
use Core\Form\Author\AuthorSubscribeForm;

class SubscribeUseCase
{
    /**
     * @param int $id
     * @param AuthorSubscribeForm $form
     * @return void
     */
    public function execute(int $id, AuthorSubscribeForm $form): void
    {
        $author = Author::getById($id);

        if (AuthorSubscribe::hasByAuthorAndPhone($author->id, $form->phone)) {
            throw new \DomainException("Номер телефона {$form->phone} уже подписан.");
        }

        AuthorSubscribe::create($author, $form->phone);
    }
}
