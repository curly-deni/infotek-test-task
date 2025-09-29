<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar navbar-expand-md fixed-top']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto align-items-center'],
        'items' => [
            ['label' => 'Top Author', 'url' => ['/top-author'], 'options' => ['class' => 'nav-item']],
            ['label' => 'Author Subscriptions', 'url' => ['/author-subscription'], 'options' => ['class' => 'nav-item']],
            ['label' => 'Authors', 'url' => ['/entities/author'], 'options' => ['class' => 'nav-item']],
            ['label' => 'Books', 'url' => ['/entities/book'], 'options' => ['class' => 'nav-item']],
            Yii::$app->user->isGuest
                ? ['label' => 'Login', 'url' => ['/login'], 'options' => ['class' => 'nav-item']]
                : '<li class="nav-item">'
                . Html::beginForm(['/logout'], 'post', ['class' => 'd-flex'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->getUser()->username . ')',
                    ['class' => 'nav-link btn btn-link text-decoration-none logout']
                )
                . Html::endForm()
                . '</li>'
        ]
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container py-4">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'],
                'options' => ['class' => 'mb-4'],
                'homeLink' => ['label' => 'Home', 'url' => Yii::$app->homeUrl],
            ]) ?>
        <?php endif ?>
        <?= Alert::widget(['options' => ['class' => 'mb-4 card p-3']]) ?>
        <?php if (!isset($this->params['disableCard']) || !$this->params['disableCard']): ?>
            <div class="content-card">
                <?= $content ?>
            </div>
        <?php else: ?>
            <?= $content ?>
        <?php endif ?>
    </div>
</main>

<footer id="footer" class="mt-auto">
    <div class="container">
        <p class="text-center">&copy; <?= env('COMPANY_NAME', 'MyCompany') ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
