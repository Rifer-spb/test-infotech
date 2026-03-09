<?php

namespace Core\Form\Auth;

use yii\base\Model;
use Core\Entity\User\User;

class SignInForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    public function rules(): array
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = User::findByUsername($this->username);

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный логин или пароль.');
            }
        }
    }

    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        $result = parent::validate($attributeNames, $clearErrors);
        $this->password = '';
        return $result;
    }
}
