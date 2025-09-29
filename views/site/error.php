<?php
/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */

/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
?>

<div class="site-error">
    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= nl2br(Html::encode($message)) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <p class="mb-3">
        <?= \Yii::t('app', 'The above error occurred while the web server was processing your request.') ?>
    </p>
    <p>
        <?= \Yii::t('app', 'Please contact us at {email} if you think this is a server error. Thank you.', [
            'email' => Html::a(
                env('MAILER_ADMIN_EMAIL', 'support@example.com'),
                'mailto:' . env('MAILER_ADMIN_EMAIL', 'support@example.com'),
                ['class' => 'text-decoration-none']
            )
        ]) ?>
    </p>
</div>
