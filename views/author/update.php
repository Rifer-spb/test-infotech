<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var Core\Form\Author\AuthorForm $model */
/** @var Core\Entity\Author\Author $author */

$this->title = 'Update Author: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $author->name, 'url' => ['view', 'id' => $author->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="author-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
