<?php

namespace app\services;

use app\events\ServiceEntityEvent;
use app\exceptions\OperationCancelledException;
use app\models\ar\BookAR;
use yii\base\Exception;
use yii\db\ActiveRecord;

class BookService extends AbstractEntityService
{
    public static function getEntityClass(): string
    {
        return BookAR::class;
    }

    public function create(array $data): BookAR
    {
        $authorIds = $data['authorIds'] ?? [];
        unset($data['authorIds']);

        $class = static::getEntityClass();
        /** @var BookAR $entity */
        $entity = new $class();
        $entity->load($data, '');

        if ($this->createScenario !== null) {
            $entity->scenario = $this->createScenario;
        }

        $event = $this->triggerEvent(ServiceEntityEvent::BEFORE_CREATE, $entity);
        if (!$event->isValid()) {
            throw new OperationCancelledException('Create operation cancelled by event.');
        }

        $this->validate($entity);

        if (!$entity->save()) {
            throw new Exception(json_encode($entity->errors));
        }

        if (!empty($authorIds)) {
            $entity->unlinkAll('authors', true);
            $entity->linkAuthors($authorIds);
        }

        $this->triggerEvent(ServiceEntityEvent::AFTER_CREATE, $entity);

        return $entity;
    }

    public function update(ActiveRecord $entity, array $data = []): ActiveRecord
    {
        $authorIds = $data['authorIds'] ?? ($entity->authorIds ?? []);
        unset($data['authorIds']);

        if (!empty($data)) {
            $entity->load($data, '');
        }

        if ($this->updateScenario !== null) {
            $entity->scenario = $this->updateScenario;
        }

        $event = $this->triggerEvent(ServiceEntityEvent::BEFORE_UPDATE, $entity);
        if (!$event->isValid()) {
            throw new OperationCancelledException('Update operation cancelled by event.');
        }

        $this->validate($entity);

        if (!$entity->save()) {
            throw new Exception(json_encode($entity->errors));
        }

        if (!empty($authorIds)) {
            $entity->unlinkAll('authors', true);
            $entity->linkAuthors($authorIds);
        }

        $this->triggerEvent(ServiceEntityEvent::AFTER_UPDATE, $entity);

        return $entity;
    }

    public function getAvailableYears(bool $activeOnly = true): array
    {
        $query = static::getEntityClass()::find()->select('year')->distinct();

        if ($activeOnly) {
            $query->andWhere(['active' => 1]);
        }

        return $query->orderBy(['year' => SORT_DESC])->column();
    }
}
