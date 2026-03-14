<?php

namespace Core\UseCase\Book;

use Core\Entity\Book\Book;
use Core\Form\Book\BookForm;
use Core\Service\Transaction\TransactionManager;

class UpdateUseCase
{
    public function __construct(
        private TransactionManager $transaction
    )
    {
    }

    /**
     * @throws \Throwable
     */
    public function execute(int $id, BookForm $form): void
    {
        $book = Book::getById($id);

        $this->transaction
            ->onProcess(function () use ($book, $form) {
                $book->edit(
                    $form->authors,
                    $form->title,
                    $form->description,
                    $form->year,
                    $form->isbn
                );

                if ($form->imageFile) {
                    $book->uploadPhoto($form->imageFile);
                }

                $book->saveOrFail();
            })
            ->onError(function () use ($book) {
                if (file_exists($book->getPublicPhotoPath())) {
                    unlink($book->getPublicPhotoPath());
                }
            })
            ->run();
    }
}
