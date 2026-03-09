<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use Core\Helper\AuthorHelper;
use Core\Entity\Book\Book;
use Core\Helper\BookHelper;

/** @var yii\web\View $this */
/** @var Core\Entity\Book\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'authors',
                'label' => 'Авторы',
                'value' => fn (Book $b) => AuthorHelper::getAuthors($b->authors),
                'format' => 'html',
            ],
            'title',
            'description:ntext',
            'year',
            'isbn',
            [
                'attribute' => 'filename',
                'label' => 'Авторы',
                'value' => fn (Book $b) => BookHelper::getPhoto($b),
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
