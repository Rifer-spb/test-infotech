<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Core\Form\Book\BookForm $bookForm */
/** @var array<int, string> $authors */
/** @var array<int, int> $years */

$this->title = 'Create Book';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'bookForm' => $bookForm,
        'authors' => $authors,
        'years' => $years,
    ]) ?>

</div>
