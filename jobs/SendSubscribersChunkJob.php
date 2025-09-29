<?php

namespace app\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class SendSubscribersChunkJob extends BaseObject implements JobInterface
{
    public array $subscriberIds;
    public int $bookId;

    public bool $useSingleJob = false;
    public bool $bulkChunk = false;
    public int $bulkChunkSize = 15;

    public function execute($queue)
    {
        if ($this->useSingleJob) {
            foreach ($this->subscriberIds as $subscriberId) {
                Yii::$app->queue->push(new SendSingleSubscriberNotificationJob([
                    'subscriberId' => $subscriberId['id'],
                    'bookId' => $this->bookId,
                ]));
            }
            return;
        }

        if ($this->bulkChunk) {
            $chunks = array_chunk($this->subscriberIds, $this->bulkChunkSize);
            foreach ($chunks as $chunk) {
                Yii::$app->queue->push(new SendBulkSubscriberNotificationJob([
                    'subscriberIds' => $chunk,
                    'bookId' => $this->bookId,
                ]));
            }
            return;
        }

        Yii::$app->queue->push(new SendBulkSubscriberNotificationJob([
            'subscriberIds' => $this->subscriberIds,
            'bookId' => $this->bookId,
        ]));
    }
}
