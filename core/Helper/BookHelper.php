<?php

namespace Core\Helper;

use yii\helpers\Html;
use Core\Entity\Book\Book;

class BookHelper
{
    public static function getPhoto(Book $book): string
    {
        return Html::img($book->getPublicPhotoPath(), [
            'style' => 'max-width: 100%; max-height: 150px; border: 1px solid #ddd; border-radius: 4px; padding: 3px;'
        ]);
    }
}
