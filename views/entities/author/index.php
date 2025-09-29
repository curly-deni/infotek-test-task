<?php

use app\models\ar\AuthorAR;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Author');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-ar-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Author'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'fullName',
                'label' => Yii::t('app', 'Full Name'),
                'value' => function (AuthorAR $model) {
                    return $model->getFullName();
                },
            ],
            [
                'attribute' => 'active',
                'format' => 'boolean',
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete} {view_subscription}',
                'buttons' => [
                    'view_subscription' => function ($url, AuthorAR $model, $key) {
                        return Html::a('View Subscription', ['author-subscription/view', 'id' => $model->id], [
                            'class' => 'btn btn-info btn-sm',
                        ]);
                    },
                ],
                'urlCreator' => function ($action, AuthorAR $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>



</div>
