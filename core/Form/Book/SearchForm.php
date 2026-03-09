<?php

namespace Core\Form\Book;

use yii\base\Model;

class SearchForm extends Model
{
    public $id;
    public $authorId;
    public $title;
    public $description;
    public $year;
    public $isbn;

    public function rules(): array
    {
        return [
            [['id', 'authorId'], 'integer'],
            [['title', 'description', 'year', 'isbn'], 'safe'],
        ];
    }
}
