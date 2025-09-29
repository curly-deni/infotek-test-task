<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\forms\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = \Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-login">
    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <p class="mb-4"><?= \Yii::t('app', 'Please fill out the following fields to login:') ?></p>

    <div class="row">
        <div class="col-12 col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => \Yii::t('app', 'Enter username')]) ?>

            <?= $form->field($model, 'password')->passwordInput(['placeholder' => \Yii::t('app', 'Enter password')]) ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"form-check mb-3\">{input} {label}</div>\n{error}",
                'labelOptions' => ['class' => 'form-check-label'],
                'inputOptions' => ['class' => 'form-check-input'],
            ])->label(\Yii::t('app', 'Remember me')) ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton(\Yii::t('app', 'Login'), ['class' => 'btn btn-primary w-100', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>