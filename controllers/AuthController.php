<?php

namespace app\controllers;

use Yii;
use Core\Entity\User\User;
use Core\Form\Auth\SignInForm;
use Core\Form\Auth\SignUpForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Core\UseCase\Auth\SignUpUseCase;

class AuthController extends AbstractController
{
    public function __construct(
        $id,
        $module,
        private SignUpUseCase $signUpUseCase,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionSignIn()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new SignInForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            return $this->try(function () use ($form) {
                $user = User::getByUsername($form->username);
                $this->createSession($user, $form->rememberMe);
                return $this->goBack();
            });
        }

        return $this->render('sign-in', [
            'model' => $form,
        ]);
    }

    public function actionSignUp()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new SignUpForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            return $this->try(function () use ($form) {
                $user = $this->signUpUseCase->execute($form);
                $this->createSession($user);
                return $this->goHome();
            });
        }

        return $this->render('sign-up', [
            'model' => $form,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    private function createSession(User $user, bool $rememberMe = true): void
    {
        $duration = $rememberMe ? 3600 * 24 * 30 : 0;
        Yii::$app->user->login($user, $duration);
    }
}
