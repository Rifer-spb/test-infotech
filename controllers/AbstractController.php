<?php

namespace app\controllers;

use Yii;

abstract class AbstractController extends \yii\web\Controller
{
    public function try(callable $callable)
    {
        try {
            return $callable();
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        } catch (\Exception $e) {
            Yii::$app->errorHandler->logException($e);

            if (YII_ENV_DEV) {
                throw $e;
            }

            Yii::$app->session->setFlash('error', Yii::t('app', 'Внутренняя ошибка сервера!'));
        }
    }
}
