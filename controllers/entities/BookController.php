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
        $data['cover_image'] = $this->processCoverImage($data['cover_image'] ?? null);
        $data['authorIds'] = \Yii::$app->request->post('author_ids');

        return $data;
    }

    protected function modifyDataBeforeUpdate(ActiveRecord $model): ActiveRecord
    {
        $model->cover_image = $this->processCoverImage($model->getOldAttribute('cover_image'));
        $model->authorIds = \Yii::$app->request->post('author_ids');

        return $model;
    }

    private function processCoverImage(?string $fallback = null): ?string
    {
        $modelShortName = getObjectShortName($this->service::getEntityClass());
        $fileRoute = saveUploadedFile($modelShortName . "[cover_image]");

        return $fileRoute ?: $fallback;
    }
}
