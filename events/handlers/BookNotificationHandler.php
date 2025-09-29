<?php

namespace app\events\handlers;

use app\events\ServiceEntityEvent;
use app\jobs\CreateSubscriberJobsJob;
use Yii;

class BookNotificationHandler
{
    public static function sendAfterCreateNotification(ServiceEntityEvent $event)
    {
        $book = $event->getEntity();

        $jobId = Yii::$app->queue->push(new CreateSubscriberJobsJob([
            'bookId' => $book->id,
        ]));

        if ($jobId) {
            Yii::info("Job #{$jobId} added to queue.", __METHOD__);
        } else {
            Yii::error("Failed to push job to queue.", __METHOD__);
        }
    }
}
