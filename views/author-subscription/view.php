<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\ar\AuthorAR $model */

$this->title = $model->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$subscriptionModel = new \yii\base\DynamicModel(['phone' => '']);
$subscriptionModel->addRule('phone', 'required');
?>

<div class="author-subscription-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Entity Manage'), ['entities/author/view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
    </p>

    <h3>Subscribe / Unsubscribe</h3>

    <?php $form = ActiveForm::begin(['method' => 'post']); ?>

    <?= $form->field($subscriptionModel, 'phone')
        ->textInput(['placeholder' => 'Enter your phone number'])
        ->label('Phone') ?>

    <div class="form-group">
        <?= Html::submitButton('Subscribe', [
            'class' => 'btn btn-success',
            'formaction' => Url::to(['subscribe', 'id' => $model->id])
        ]) ?>
        <?= Html::submitButton('Unsubscribe', [
            'class' => 'btn btn-danger',
            'formaction' => Url::to(['unsubscribe', 'id' => $model->id])
        ]) ?>
        <?= Html::submitButton('Check', [
            'class' => 'btn btn-danger',
            'formaction' => Url::to(['check', 'id' => $model->id])
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
