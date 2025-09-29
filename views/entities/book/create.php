<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ar\BookAR $model */

$this->title = Yii::t('app', 'Create Book');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Book'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-ar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
