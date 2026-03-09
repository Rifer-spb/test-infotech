<?php

namespace Core\Form\Author;

use yii\base\Model;

class AuthorSubscribeForm extends Model
{
    public $phone;

    public function rules(): array
    {
        return [
            [['phone'], 'required'],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'match',
                'pattern' => '/^\+?[0-9\s\-\(\)]{10,20}$/',
                'message' => 'Введите корректный номер телефона'
            ],
            [['phone'], 'filter', 'filter' => function($value) {
                return preg_replace('/[^\d\+]/', '', $value);
            }],
        ];
    }
}
