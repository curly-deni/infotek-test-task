<?php

namespace app\services;

use app\events\SubscribeEvent;
use app\models\ar\AuthorAR;
use app\models\ar\SubscriberAR;

class SubscriberService extends AbstractEntityService
{
    public static function getEntityClass(): string
    {
        return SubscriberAR::class;
    }

    public function subscribeByPhone(AuthorAR $author, string $phone, ?int $userId = null): SubscriberAR
    {
        $subscriber = SubscriberAR::findOne(['phone' => $phone]);
        if (!$subscriber) {
            $subscriber = $this->create([
                'phone' => $phone,
                'user_id' => $userId,
            ]);
        } elseif (!$subscriber->active) {
            $subscriber->active = true;
            $subscriber->save(false);
        }

        $event = new SubscribeEvent($subscriber, $author);
        $this->trigger(SubscribeEvent::BEFORE_SUBSCRIBE, $event);
        if (!$event->isValid()) {
            throw new \RuntimeException('Subscribe operation cancelled by event.');
        }

        $author->link('subscribers', $subscriber);

        $this->trigger(SubscribeEvent::AFTER_SUBSCRIBE, $event);

        return $subscriber;
    }

    public function unsubscribeByPhone(AuthorAR $author, string $phone): void
    {
        $subscriber = SubscriberAR::findOne(['phone' => $phone]);
        if (!$subscriber) {
            return;
        }

        $event = new SubscribeEvent($subscriber, $author);
        $this->trigger(SubscribeEvent::BEFORE_UNSUBSCRIBE, $event);
        if (!$event->isValid()) {
            throw new \RuntimeException('Unsubscribe operation cancelled by event.');
        }

        $author->unlink('subscribers', $subscriber, true);

        $this->trigger(SubscribeEvent::AFTER_UNSUBSCRIBE, $event);
    }

    public function isSubscribed(AuthorAR $author, ?string $phone): bool
    {
        $query = $author->getSubscribers();
        $query->andWhere(['phone' => $phone]);

        return $query->exists();
    }


}
