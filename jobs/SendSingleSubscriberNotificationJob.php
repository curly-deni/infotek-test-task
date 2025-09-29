<?php

namespace app\jobs;

use app\models\ar\AuthorAR;
use app\models\ar\BookAR;
use app\models\ar\SubscriberAR;
use app\notifications\NotificationInterface;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class SendSingleSubscriberNotificationJob extends BaseObject implements JobInterface
{
    public int $subscriberId;
    public int $bookId;
    public string $notifierClass = NotificationInterface::class;

    public function execute($queue): void
    {
        $subscriber = SubscriberAR::findOne($this->subscriberId);
        $book = BookAR::findOne($this->bookId);
        if (!$subscriber || !$book) {
            return;
        }

        /** @var NotificationInterface $notifier */
        $notifier = Yii::$container->get($this->notifierClass);

        $authors = AuthorAR::find()
            ->alias('a')
            ->innerJoin('author_subscribers sa', 'sa.author_id = a.id')
            ->where(['sa.subscriber_id' => $subscriber->id])
            ->andWhere(['a.id' => array_column($book->authors, 'id')])
            ->select(['a.first_name', 'a.middle_name', 'a.last_name'])
            ->asArray()
            ->all();

        $authorNames = array_map(
            fn($a) => getFullName($a),
            $authors
        );

        if (!$authorNames) {
            return;
        }

        $authorsList = implode(', ', $authorNames);
        $message = "Новая книга от авторов, на которых вы подписаны: {$authorsList}. Книга: {$book->title}";

        $notifier->send($subscriber->phone, $message);
    }


}
