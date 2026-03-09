<?php

namespace Core\Helper;

use yii\helpers\Html;
use Core\Entity\Author\Author;

class AuthorHelper
{
    public static function getAuthors(array $array): string
    {
        if (empty($array)) {
            return '<span class="not-set">Нет авторов</span>';
        }

        $func = function (Author $author) {
            return Html::tag('span', $author->name);
        };

        return implode(', ', array_map($func, $array));
    }
}
