<?php

namespace app\events;

use app\models\ar\AuthorAR;
use app\models\ar\SubscriberAR;

class SubscribeEvent extends ServiceEntityEvent
{
    public const BEFORE_SUBSCRIBE = 'beforeSubscribe';
    public const AFTER_SUBSCRIBE = 'afterSubscribe';
    public const BEFORE_UNSUBSCRIBE = 'beforeUnsubscribe';
    public const AFTER_UNSUBSCRIBE = 'afterUnsubscribe';

    protected AuthorAR $author;

    public function __construct(SubscriberAR $subscriber, AuthorAR $author)
    {
        parent::__construct($subscriber);
        $this->author = $author;
    }

    public function getAuthor(): AuthorAR
    {
        return $this->author;
    }
}
