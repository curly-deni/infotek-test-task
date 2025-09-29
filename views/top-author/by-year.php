<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'Top authors for {year}', ['year' => $year]);
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (!empty($authors)): ?>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th><?= Yii::t('app', 'Author Name') ?></th>
            <th><?= Yii::t('app', 'Number of Books') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($authors as $author): ?>
            <tr>
                <td><?= Html::encode(getFullName($author)) ?></td>
                <td><?= Html::encode($author['books_count']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p><?= Yii::t('app', 'No authors found for this year.') ?></p>
<?php endif; ?>

<p>
    <?= Html::a(Yii::t('app', 'Select another year'), ['top-author/index'], ['class' => 'btn btn-primary']) ?>
</p>
