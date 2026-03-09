<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Core\Entity\Book\Book $book */
/** @var Core\Form\Book\BookForm $bookForm */
/** @var array<int, string> $authors */
/** @var array<int, int> $years */

$this->title = 'Update Book: ' . $book->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $book->title, 'url' => ['view', 'id' => $book->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="book-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'book' => $book,
        'bookForm' => $bookForm,
        'authors' => $authors,
        'years' => $years,
    ]) ?>

</div>
