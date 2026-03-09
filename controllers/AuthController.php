<?php

namespace app\controllers;

use Yii;
use Core\Entity\User\User;
use Core\Form\Auth\SignInForm;
use Core\Form\Auth\SignUpForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AuthController extends Controller
{
    public function __construct(
        $id,
        $module,
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
            try {
                $user = User::getByUsername($form->username);
                $this->createSession($user, $form->rememberMe);
                return $this->goBack();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', Yii::t('app', 'Внутренняя ошибка сервера!'));
            }
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
            try {
                $user = User::create($form->username, $form->password);
                $this->createSession($user);
                return $this->goHome();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', Yii::t('app', 'Внутренняя ошибка сервера!'));
            }
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
