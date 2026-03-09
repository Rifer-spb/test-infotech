<?php

namespace Core\UseCase\Auth;

use Core\Entity\User\User;
use Core\Form\Auth\SignUpForm;
use Core\Service\Transaction\TransactionManager;

class SignUpUseCase
{
    public function __construct(
        private TransactionManager $transaction
    )
    {

    }

    /**
     * @param SignUpForm $form
     * @return User
     * @throws \Throwable
     */
    public function execute(SignUpForm $form): User
    {
        return $this->transaction->onProcess(function () use ($form) {
            return User::create($form->username, $form->password);
        })->run();
    }
}
