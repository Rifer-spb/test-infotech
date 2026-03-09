<?php

namespace Core\Entity\Book;

use Core\Entity\AbstractEntity;
use Core\Entity\Author\Author;

/**
 * @property int $book_id Книга
 * @property int $author_id Автор
 */
class BookAuthor extends AbstractEntity
{
    public static function tableName(): string
    {
        return '{{%books_authors}}';
    }

    public static function create(Author $author, Book $book): void
    {
        $model = new static();
        $model->author_id = $author->id;
        $model->book_id = $book->id;
        $model->saveOrFail();
    }
}
