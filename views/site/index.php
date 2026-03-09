<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
