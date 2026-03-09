<?php

namespace Core\Form\Author;

use yii\base\Model;
use Core\Entity\Author\Author;

class AuthorForm extends Model
{
    public $name;

    public function __construct(Author $author = null, $config = [])
    {
        if ($author !== null) {
            $this->name = $author->name;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
            [['name'], 'trim'],
        ];
    }
}
