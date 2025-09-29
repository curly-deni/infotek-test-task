<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ar\BookAR $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Book'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-ar-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'year',
            'description:ntext',
            'isbn',

            [
                'attribute' => 'cover_image',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->cover_image
                        ? Html::img($model->cover_image, ['width' => '120'])
                        : '(no image)';
                },
            ],

            [
                'label' => 'Authors',
                'format' => 'html',
                'value' => function ($model) {
                    return implode('<br>', array_map(function ($author) {
                        return Html::a(Html::encode($author->fullName), ['entities/author/view', 'id' => $author->id]);
                    }, $model->authors));
                },
            ],

            'active:boolean',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>


</div>
