<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Authors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-subscription-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'first_name',
            'middle_name',
            'last_name',
            'active:boolean',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {view_entity}',
                'buttons' => [
                    'view_entity' => function ($url, $model, $key) {
                        return Html::a('View Entity', ['entities/author/view', 'id' => $model->id], [
                            'class' => 'btn btn-primary btn-sm',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>
