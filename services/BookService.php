<?php

namespace app\services;

use app\models\ar\BookAR;
use yii\db\ActiveRecord;

class BookService extends AbstractEntityService
{
    public static function getEntityClass(): string
    {
        return BookAR::class;
    }

    protected function beforeCreate(array $data): array
    {
        $data['cover_image'] = $this->processCoverImage($data['cover_image'] ?? null);
        $data['authorIds'] = $data['authorIds'] ?? [];
        return $data;
    }

    protected function afterCreate(ActiveRecord $entity): void
    {
        $authorIds = $entity->authorIds ?? [];
        if (!empty($authorIds)) {
            $entity->linkAuthors($authorIds);
        }
    }

    protected function beforeUpdate(ActiveRecord $entity, array $data): array
    {
        $data['cover_image'] = $this->processCoverImage($entity->cover_image);
        $data['authorIds'] = $data['authorIds'] ?? ($entity->authorIds ?? []);
        return $data;
    }

    protected function afterUpdate(ActiveRecord $entity): void
    {
        $authorIds = $entity->authorIds ?? [];
        if (!empty($authorIds)) {
            $entity->linkAuthors($authorIds);
        }
    }

    protected function processCoverImage(?string $fallback = null): ?string
    {
        $modelShortName = getObjectShortName(static::getEntityClass());
        $file = \yii\web\UploadedFile::getInstanceByName("{$modelShortName}[cover_image]");

        if ($file) {
            $fileRoute = saveUploadedFile("{$modelShortName}[cover_image]");
            return $fileRoute ?: $fallback;
        }

        return $fallback;
    }
}
