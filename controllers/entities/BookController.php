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
        $modelShortName = getObjectShortName($this->service::getEntityClass());
        $fileRoute = saveUploadedFile($modelShortName . "[cover_image]");

        if ($fileRoute) {
            $data['cover_image'] = $fileRoute;
        }

        $data['authorIds'] = \Yii::$app->request->post('author_ids');

        return $data;
    }

    protected function modifyDataBeforeUpdate(\yii\db\ActiveRecord $model): \yii\db\ActiveRecord
    {
        $modelShortName = getObjectShortName($this->service::getEntityClass());
        $fileRoute = saveUploadedFile($modelShortName . "[cover_image]");

        if ($fileRoute) {
            $model->cover_image = $fileRoute;
        } else {
            $model->cover_image = $model->getOldAttribute('cover_image');
        }

        $model->authorIds = \Yii::$app->request->post('author_ids');

        return $model;
    }

}
