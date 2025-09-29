<?php

namespace app\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class SendSubscribersChunkJob extends BaseObject implements JobInterface
{
    public array $subscriberIds;
    public int $bookId;

    public function execute($queue): void
    {
        foreach ($this->subscriberIds as $subscriber) {
            Yii::$app->queue->push(new SendSubscriberNotificationJob([
                'subscriberId' => is_array($subscriber) ? $subscriber['id'] : $subscriber,
                'bookId'       => $this->bookId,
                'bulk'         => false,
            ]));
        }
    }
}
