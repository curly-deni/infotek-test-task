<?php

use app\models\ar\BookAR;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Book');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-ar-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Book'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'cover_image',
                'format' => 'html',
                'value' => function (BookAR $model) {
                    return $model->cover_image
                        ? Html::img($model->cover_image, ['width' => '60'])
                        : '(no image)';
                },
            ],

            'title',
            'year',
            'isbn',

            [
                'label' => 'Authors',
                'format' => 'html',
                'value' => function (BookAR $model) {
                    return implode('<br>', array_map(function ($author) {
                        return Html::a(Html::encode($author->fullName), ['entities/author/view', 'id' => $author->id]);
                    }, $model->authors));
                },
            ],

            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, BookAR $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
