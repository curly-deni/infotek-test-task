<?php

namespace app\jobs;

use app\models\ar\AuthorAR;
use app\models\ar\BookAR;
use app\models\ar\SubscriberAR;
use app\notifications\NotificationInterface;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class SendBulkSubscriberNotificationJob extends BaseObject implements JobInterface
{
    public array $subscriberIds = [];
    public int $bookId;
    public string $notifierClass = NotificationInterface::class;

    public function execute($queue): void
    {
        $book = BookAR::findOne($this->bookId);
        if (!$book) {
            return;
        }

        /** @var NotificationInterface $notifier */
        $notifier = Yii::$container->get($this->notifierClass);

        $authors = AuthorAR::find()
            ->alias('a')
            ->innerJoin('book_authors ba', 'ba.author_id = a.id')
            ->where(['ba.book_id' => $book->id])
            ->select(['a.first_name', 'a.middle_name', 'a.last_name'])
            ->asArray()
            ->all();

        if (!$authors) {
            return;
        }

        $authorNames = array_map(
            fn($a) => getFullName($a),
            $authors
        );

        $authorsList = implode(', ', $authorNames);
        $message = "Новая книга от авторов: {$authorsList}. Книга: {$book->title}";

        $ids = array_column($this->subscriberIds, 'id');
        $phones = SubscriberAR::find()
            ->where(['id' => $ids])
            ->select('phone')
            ->column();

        if ($phones) {
            $notifier->bulkSend($phones, $message);
        }
    }
}
