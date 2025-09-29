<?php

namespace app\jobs;

use app\models\ar\SubscriberAR;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class CreateSubscriberJobsJob extends BaseObject implements JobInterface
{
    public int $bookId;
    public int $chunkSize = 50;

    /** @var bool — true = bulk режим, false = single режим */
    public bool $bulk = true;

    public function execute($queue)
    {
        $subscriberIds = SubscriberAR::find()
            ->alias('s')
            ->innerJoin('author_subscribers sa', 'sa.subscriber_id = s.id')
            ->innerJoin('book_authors ba', 'ba.author_id = sa.author_id')
            ->where(['ba.book_id' => $this->bookId])
            ->select('s.id')
            ->distinct()
            ->column();

        if ($this->bulk) {
            foreach (array_chunk($subscriberIds, $this->chunkSize) as $chunk) {
                Yii::$app->queue->push(new SendSubscriberNotificationJob([
                    'subscriberIds' => $chunk,
                    'bookId'        => $this->bookId,
                    'bulk'          => true,
                ]));
            }
        } else {
            foreach (array_chunk($subscriberIds, $this->chunkSize) as $chunk) {
                Yii::$app->queue->push(new SendSubscribersChunkJob([
                    'subscriberIds' => $chunk,
                    'bookId'        => $this->bookId,
                ]));
            }
        }
    }
}
