<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'Select year for top authors');
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (!empty($years)): ?>
    <ul>
        <?php foreach ($years as $year): ?>
            <li>
                <?= Html::a(Html::encode($year), ['top-author/view', 'id' => $year]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><?= Yii::t('app', 'No years found.') ?></p>
<?php endif; ?>
