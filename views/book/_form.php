<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use Core\Helper\BookHelper;

/** @var yii\web\View $this */
/** @var Core\Entity\Book\Book|null $book */
/** @var Core\Form\Book\BookForm $bookForm */
/** @var yii\widgets\ActiveForm $form */
/** @var array<int, string> $authors */
/** @var array<int, int> $years */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($bookForm, 'authors')->dropDownList($authors, [
        'multiple' => 'multiple',
        'prompt' => 'Выберите автора...'
    ]) ?>

    <?= $form->field($bookForm, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($bookForm, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($bookForm, 'year')->dropDownList($years, ['prompt' => 'Выберите год выпуска...']) ?>

    <?= $form->field($bookForm, 'isbn')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="control-label">Обложка книги</label>

        <!-- Показываем текущее фото если есть (при редактировании) -->
        <?php if (isset($book) && $book->filename): ?>
            <div class="well well-sm" style="margin-bottom: 15px; background: #f8f9fa;">
                <div class="row">
                    <div class="col-sm-3">
                        <?= BookHelper::getPhoto($book) ?>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted">
                            <strong>Текущее фото:</strong> <?= $book->filename ?><br>
                            <small>Загрузите новое фото, чтобы заменить текущее</small>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Поле загрузки файла -->
        <?= $form->field($bookForm, 'imageFile', [
            'options' => ['class' => 'form-group'],
            'template' => "{label}\n<div class=\"col-sm-12\">{input}\n{hint}\n{error}</div>"
        ])->fileInput([
            'accept' => 'image/*', // Только изображения
            'class' => 'form-control-file', // Bootstrap класс для file input
            'style' => 'padding: 5px;',
        ])->label(false) // Убираем label, так как мы уже выше написали
        ?>

        <!-- Подсказка -->
        <div class="text-muted small" style="margin-top: 5px; padding-left: 15px;">
            <i class="glyphicon glyphicon-info-sign"></i>
            Разрешены форматы: JPG, PNG, WEBP. Максимальный размер: 5MB
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
