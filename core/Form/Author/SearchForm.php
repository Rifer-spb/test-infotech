<?php

namespace Core\Form\Author;

use yii\base\Model;

/**
 * AuthorSearchForm represents the model behind the search form of `Core\Entity\Author\Author`.
 */
class SearchForm extends Model
{
    public $id;
    public $name;

    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name'], 'string'],
        ];
    }
}
