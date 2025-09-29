<?php

namespace app\services;

use app\models\ar\AuthorAR;
use app\models\ar\BookAR;
use app\models\ar\SubscriberAR;
use app\notifications\NotificationInterface;
use Yii;

class SubscriberNotificationService
{
    public function buildMessage(BookAR $book, ?int $subscriberId = null): ?string
    {
        if (!$book) {
            return null;
        }

        if ($subscriberId) {
            $authors = AuthorAR::find()
                ->alias('a')
                ->innerJoin('author_subscribers sa', 'sa.author_id = a.id')
                ->where(['sa.subscriber_id' => $subscriberId])
                ->andWhere(['a.id' => array_column($book->authors, 'id')])
                ->select(['a.first_name', 'a.middle_name', 'a.last_name'])
                ->asArray()
                ->all();
        } else {
            $authors = AuthorAR::find()
                ->alias('a')
                ->innerJoin('book_authors ba', 'ba.author_id = a.id')
                ->where(['ba.book_id' => $book->id])
                ->select(['a.first_name', 'a.middle_name', 'a.last_name'])
                ->asArray()
                ->all();
        }

        if (!$authors) {
            return null;
        }

        $authorNames = array_map(fn($a) => getFullName($a), $authors);
        $authorsList = implode(', ', $authorNames);

        return Yii::t('app', 'New book from authors: {authors}. Title: {title}', [
            'authors' => $authorsList,
            'title'   => $book->title,
        ]);
    }

    public function sendSingle(int $subscriberId, BookAR $book, NotificationInterface $notifier): void
    {
        $subscriber = SubscriberAR::findOne($subscriberId);
        if (!$subscriber) {
            return;
        }

        $message = $this->buildMessage($book, $subscriberId);
        if ($message) {
            $notifier->send($subscriber->phone, $message);
        }
    }

    public function sendBulk(array $subscriberIds, BookAR $book, NotificationInterface $notifier): void
    {
        $ids = array_column($subscriberIds, 'id');
        $phones = SubscriberAR::find()
            ->where(['id' => $ids])
            ->select('phone')
            ->column();

        $message = $this->buildMessage($book);
        if ($message && $phones) {
            $notifier->bulkSend($phones, $message);
        }
    }
}
