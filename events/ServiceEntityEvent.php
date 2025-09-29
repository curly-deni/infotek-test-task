<?php

namespace app\events;

use yii\base\Event;
use yii\db\ActiveRecord;

class ServiceEntityEvent extends Event
{
    public const BEFORE_CREATE = 'beforeCreate';
    public const AFTER_CREATE  = 'afterCreate';
    public const BEFORE_UPDATE = 'beforeUpdate';
    public const AFTER_UPDATE  = 'afterUpdate';
    public const BEFORE_DELETE = 'beforeDelete';
    public const AFTER_DELETE  = 'afterDelete';

    protected ActiveRecord $entity;
    protected bool $isValid = true;

    public function __construct(ActiveRecord $entity, $config = [])
    {
        $this->entity = $entity;
        parent::__construct($config);
    }

    public function getEntity(): ActiveRecord
    {
        return $this->entity;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $value): void
    {
        $this->isValid = $value;
    }
}
