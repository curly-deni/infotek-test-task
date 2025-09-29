<?php

use app\models\ar\AuthorAR;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ar\BookAR $model */
/** @var yii\widgets\ActiveForm $form */
/** @var AuthorAR[] $authors */

$authors = AuthorAR::find()->all();

$authorItems = ArrayHelper::map($authors, 'id', 'fullName');

$selectedAuthors = isset($model->id) ? ArrayHelper::getColumn($model->authors, 'id') : [];

?>

<div class="book-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'year')->input('number') ?>
    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
    <?= $form->field($model, 'cover_image')->fileInput() ?>
    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::label(Yii::t('app', 'Authors')) ?>
        <?= Html::dropDownList('author_ids', $selectedAuthors, $authorItems, [
            'class' => 'form-control',
            'multiple' => true,
            'size' => 10,
        ]) ?>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>