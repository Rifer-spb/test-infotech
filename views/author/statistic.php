<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\ActiveForm;
use Core\Helper\YearHelper;

/** @var yii\web\View $this */
/** @var Core\Form\Author\StatisticForm $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Топ 10 авторов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Форма фильтрации -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="glyphicon glyphicon-filter"></i> Фильтр
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['author/statistic'],
                'options' => ['class' => 'form-inline'],
            ]); ?>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'year')->dropDownList(
                        YearHelper::getList(),
                        [
                            'prompt' => 'Все годы',
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()' // автосабмит при выборе
                        ]
                    )->label('Год') ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'limit')->dropDownList(
                        [5 => '5', 10 => '10', 20 => '20', 50 => '50'],
                        [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()'
                        ]
                    )->label('Количество') ?>
                </div>

                <div class="col-md-3" style="padding-top: 25px;">
                    <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Применить', [
                        'class' => 'btn btn-primary'
                    ]) ?>
                    <?= Html::a('Сбросить', ['author/statistic'], [
                        'class' => 'btn btn-default'
                    ]) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'booksCount',
                'label' => 'Количество книг',
                'value' => function(array $model) {
                    return Html::encode($model['booksCount']);
                }
            ]
        ],
    ]); ?>

</div>
