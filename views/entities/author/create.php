<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ar\AuthorAR $model */

$this->title = Yii::t('app', 'Create Author');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Author'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-ar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
