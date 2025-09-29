<?php

namespace app\controllers\entities;

use app\services\BookService;
use yii\db\ActiveRecord;

class BookController extends AbstractEntityController
{
    public static function getEntityServiceClass(): string
    {
        return BookService::class;
    }

    protected function modifyDataBeforeCreate(array $data): array
    {
        $data['authorIds'] = \Yii::$app->request->post('author_ids');
        return $data;
    }

    protected function modifyDataBeforeUpdate(ActiveRecord $model): ActiveRecord
    {
        $model->authorIds = \Yii::$app->request->post('author_ids');
        return $model;
    }
}
