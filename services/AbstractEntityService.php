<?php

namespace app\services;

use app\events\ServiceEntityEvent;
use app\exceptions\OperationCancelledException;
use yii\base\Component;
use yii\base\Exception;
use yii\db\ActiveRecord;

abstract class AbstractEntityService extends Component
{
    abstract public static function getEntityClass(): string;

    protected ?string $createScenario = null;
    protected ?string $updateScenario = null;

    public function create(array $data): ActiveRecord
    {
        $entity = $this->initEntity($data, $this->createScenario);

        $this->checkBeforeEvent(ServiceEntityEvent::BEFORE_CREATE, $entity);

        $this->save($entity);

        $this->triggerEvent(ServiceEntityEvent::AFTER_CREATE, $entity);

        return $entity;
    }

    public function update(ActiveRecord $entity, array $data = []): ActiveRecord
    {
        if (!empty($data)) {
            $entity->load($data, '');
        }

        if ($this->updateScenario !== null) {
            $entity->scenario = $this->updateScenario;
        }

        $this->checkBeforeEvent(ServiceEntityEvent::BEFORE_UPDATE, $entity);

        $this->save($entity);

        $this->triggerEvent(ServiceEntityEvent::AFTER_UPDATE, $entity);

        return $entity;
    }

    public function delete(ActiveRecord $entity): void
    {
        $this->checkBeforeEvent(ServiceEntityEvent::BEFORE_DELETE, $entity);

        $entity->delete();

        $this->triggerEvent(ServiceEntityEvent::AFTER_DELETE, $entity);
    }

    protected function save(ActiveRecord $entity): void
    {
        $this->validate($entity);

        if (!$entity->save()) {
            $errors = implode(', ', array_map(fn($e) => implode(', ', $e), $entity->errors));
            throw new Exception($errors);
        }
    }

    protected function validate(array|ActiveRecord $data): void
    {
        if (is_array($data)) {
            $class = static::getEntityClass();
            /** @var ActiveRecord $entity */
            $entity = new $class();
            $entity->load($data, '');
        } else {
            /** @var ActiveRecord $entity */
            $entity = $data;
        }

        if (!$entity->validate()) {
            $errors = implode(', ', array_map(fn($e) => implode(', ', $e), $entity->errors));
            throw new Exception($errors);
        }
    }


    protected function initEntity(array $data, ?string $scenario = null): ActiveRecord
    {
        $class = static::getEntityClass();
        /** @var ActiveRecord $entity */
        $entity = new $class();
        $entity->load($data, '');

        if ($scenario !== null) {
            $entity->scenario = $scenario;
        }

        return $entity;
    }

    protected function checkBeforeEvent(string $type, ActiveRecord $entity): void
    {
        $event = $this->triggerEvent($type, $entity);
        if (!$event->isValid()) {
            throw new OperationCancelledException("Operation cancelled by event: $type");
        }
    }

    protected function triggerEvent(string $type, ActiveRecord $entity): ServiceEntityEvent
    {
        $event = new ServiceEntityEvent($entity);
        $this->trigger($type, $event);
        return $event;
    }
}
