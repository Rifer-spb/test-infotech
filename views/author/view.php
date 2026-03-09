<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var Core\Entity\Author\Author $model */
/** @var Core\Form\Author\AuthorSubscribeForm $subscribeForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

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
            'name',
        ],
    ]) ?>

    <br/>
    <br/>

    <div class="author-subscribe">
        <h4 class="panel-title">Подписка на новые книги</h4>

        <div class="author-subscribe">
            <?php $form = ActiveForm::begin([
                'action' => ['author/subscribe', 'id' => $model->id],
            ]); ?>

            <?= $form->field($subscribeForm, 'phone')->textInput([
                'placeholder' => '+7 (999) 999-99-99',
                'class' => 'form-control',
                'type' => 'tel'
            ])->hint('Формат: +7 (999) 999-99-99 или 89999999999')->label(false) ?>

            <div class="form-group">
                <?= Html::submitButton('Подписаться', [
                    'class' => 'btn btn-success btn-block',
                    'name' => 'subscribe-button'
                ]) ?>
            </div>

            <i class="glyphicon glyphicon-info-sign"></i>
            После подписки вы будете получать уведомления о новых книгах этого автора.

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
