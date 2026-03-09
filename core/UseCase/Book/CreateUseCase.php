<?php

namespace Core\UseCase\Book;

use Core\Entity\Book\Book;
use Core\Form\Book\BookForm;
use Core\Service\Transaction\TransactionManager;

class CreateUseCase
{
    public function __construct(
        private TransactionManager $transaction
    )
    {
    }

    /**
     * @throws \Throwable
     */
    public function execute(BookForm $form): Book
    {
        return $this->transaction
            ->onProcess(function () use ($form) {
                $book = Book::create(
                    $form->authors,
                    $form->title,
                    $form->description,
                    $form->year,
                    $form->isbn
                );

                if ($form->imageFile) {
                    $book->uploadPhoto($form->imageFile);
                    $book->saveOrFail();
                }

                return $book;
            })
            ->run();
    }
}
