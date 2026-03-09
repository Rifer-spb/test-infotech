<?php

namespace Core\Form\Auth;

use yii\base\Model;
use Core\Entity\User\User;

class SignUpForm extends Model
{
    public $username;
    public $password;

    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'email'],

            ['password', 'required'],
            ['password', 'string', 'min' => 8],
        ];
    }
}
