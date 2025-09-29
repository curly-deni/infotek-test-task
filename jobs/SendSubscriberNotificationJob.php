<?php

namespace app\jobs;

use app\models\ar\BookAR;
use app\notifications\NotificationInterface;
use app\services\SubscriberNotificationService;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class SendSubscriberNotificationJob extends BaseObject implements JobInterface
{
    /** @var int|null — если null, значит bulk-режим */
    public ?int $subscriberId = null;

    /** @var array<int,array{id:int}> — список подписчиков для bulk */
    public array $subscriberIds = [];

    public int $bookId;

    public bool $bulk = false;

    public string $notifierClass = NotificationInterface::class;

    public string $serviceClass = SubscriberNotificationService::class;

    public function execute($queue): void
    {
        $book = BookAR::findOne($this->bookId);
        if (!$book) {
            return;
        }

        /** @var NotificationInterface $notifier */
        $notifier = Yii::$container->get($this->notifierClass);
        $service = Yii::$container->get($this->serviceClass);

        if ($this->bulk) {
            $service->sendBulk($this->subscriberIds, $book, $notifier);
        } else {
            if (!$this->subscriberId) {
                return;
            }
            $service->sendSingle($this->subscriberId, $book, $notifier);
        }
    }
}
