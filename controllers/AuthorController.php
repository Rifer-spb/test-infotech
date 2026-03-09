<?php

namespace app\controllers;

use Core\Form\Author\AuthorSubscribeForm;
use Core\Form\Author\StatisticForm;
use Core\UseCase\Author\SubscribeUseCase;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Core\Entity\Author\Author;
use yii\web\NotFoundHttpException;
use Core\Form\Author\SearchForm;
use Core\Form\Author\AuthorForm;
use Core\UseCase\Author\CreateUseCase;
use Core\UseCase\Author\UpdateUseCase;
use Core\UseCase\Author\SearchUseCase;
use Core\UseCase\Author\DeleteUseCase;
use Core\UseCase\Author\StatisticUseCase;

class AuthorController extends AbstractController
{
    public function __construct(
        $id,
        $module,
        private SearchUseCase $searchUseCase,
        private CreateUseCase $createUseCase,
        private UpdateUseCase $updateUseCase,
        private DeleteUseCase $deleteUseCase,
        private StatisticUseCase $statisticUseCase,
        private SubscribeUseCase $subscribeUseCase,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'statistic', 'subscribe'],
                            'allow' => true,
                            'roles' => ['?', '@']
                        ],
                        [
                            'actions' => ['create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['@']
                        ],
                    ],
                    'denyCallback' => function ($rule, $action) {
                        throw new \yii\web\ForbiddenHttpException('У вас нет прав для этого действия.');
                    },
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $form = new SearchForm();
        $form->load(Yii::$app->request->queryParams);
        $dataProvider = $this->searchUseCase->execute($form);

        return $this->render('index', [
            'searchModel' => $form,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $form = new AuthorForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->try(function () use ($form) {
                $author =  $this->createUseCase->execute($form);
                Yii::$app->session->setFlash('success', 'Успешно создано');
                return $this->redirect(['view', 'id' => $author->id]);
            });
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $author = Author::findByIdOrHttpNotFound($id);
        $subscribeForm = new AuthorSubscribeForm();

        return $this->render('view', [
            'model' => $author,
            'subscribeForm' => $subscribeForm
        ]);
    }

    public function actionSubscribe($id)
    {
        $author = Author::findByIdOrHttpNotFound($id);

        $form = new AuthorSubscribeForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->try(function () use ($form, $author) {
                $this->subscribeUseCase->execute($author->id, $form);
                Yii::$app->session->setFlash('success', 'Подписка успешно оформлена');
            });

            return $this->redirect(['view', 'id' => $author->id]);
        }

        return $this->render('update', [
            'author' => $author,
            'model' => $form,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $author = Author::findByIdOrHttpNotFound($id);

        $form = new AuthorForm($author);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->try(function () use ($form, $author) {
                $this->updateUseCase->execute($author->id, $form);
                Yii::$app->session->setFlash('success', 'Успешно обновлено');
            });

            return $this->redirect(['view', 'id' => $author->id]);
        }

        return $this->render('update', [
            'author' => $author,
            'model' => $form,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $author = Author::findByIdOrHttpNotFound($id);

        $this->try(function () use ($author) {
            $this->deleteUseCase->execute($author->id);
            Yii::$app->session->setFlash('success', 'Успешно удалено');
        });

        return $this->redirect(['index']);
    }

    public function actionStatistic()
    {
        $form = new StatisticForm();
        $form->load(Yii::$app->request->queryParams);
        $dataProvider = $this->statisticUseCase->execute($form);

        return $this->render('statistic', [
            'model' => $form,
            'dataProvider' => $dataProvider,
        ]);
    }
}
