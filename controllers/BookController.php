<?php

namespace app\controllers;

use Yii;
use Core\Entity\Book\Book;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Core\Entity\Author\Author;
use Core\Helper\YearHelper;
use Core\Form\Book\SearchForm;
use Core\Form\Book\BookForm;
use Core\UseCase\Book\SearchUseCase;
use Core\UseCase\Book\CreateUseCase;
use Core\UseCase\Book\DeleteUseCase;
use Core\UseCase\Book\UpdateUseCase;
use yii\web\UploadedFile;

class BookController extends AbstractController
{
    public function __construct(
        $id,
        $module,
        private SearchUseCase $searchUseCase,
        private CreateUseCase $createUseCase,
        private DeleteUseCase $deleteUseCase,
        private UpdateUseCase $updateUseCase,
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
                            'actions' => ['index', 'view'],
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
        $form = new BookForm();

        if (Yii::$app->request->isPost) {
            $form->load(Yii::$app->request->post());
            $form->imageFile = UploadedFile::getInstance($form, 'imageFile');

            if ($form->validate()) {
                $this->try(function () use ($form) {
                    $book = $this->createUseCase->execute($form);
                    Yii::$app->session->setFlash('success', 'Книга успешно добавлена.');
                    return $this->redirect(['view', 'id' => $book->id]);
                });
            }
        }

        return $this->render('create', [
            'bookForm' => $form,
            'authors' => Author::getAllForSelect(),
            'years' => YearHelper::getList(2020)
        ]);
    }

    public function actionView($id)
    {
        $book = Book::findByIdOrHttpNotFound($id);

        return $this->render('view', [
            'model' => $book,
        ]);
    }

    public function actionUpdate($id)
    {
        $book = Book::findByIdOrHttpNotFound($id);

        $form = new BookForm($book);

        if (Yii::$app->request->isPost) {
            $form->load(Yii::$app->request->post());
            $form->imageFile = UploadedFile::getInstance($form, 'imageFile');

            if ($form->validate()) {
                $this->try(function () use ($book, $form) {
                    $this->updateUseCase->execute($book->id, $form);
                    Yii::$app->session->setFlash('success', 'Книга успешно обновлена.');
                    return $this->redirect(['view', 'id' => $book->id]);
                });
            }
        }

        return $this->render('update', [
            'book' => $book,
            'bookForm' => $form,
            'authors' => Author::getAllForSelect(),
            'years' => YearHelper::getList(2020)
        ]);
    }

    public function actionDelete($id)
    {
        $book = Book::findByIdOrHttpNotFound($id);

        $this->try(function () use ($book) {
            $this->deleteUseCase->execute($book->id);
            Yii::$app->session->setFlash('success', 'Книга успешно удалена.');
        });

        return $this->redirect(['index']);
    }
}
