<?php
namespace app\bootstrap;

use app\events\handlers\BookNotificationHandler;
use app\events\ServiceEntityEvent;
use yii\base\BootstrapInterface;
use yii\base\Event;
use app\services\BookService;

class NotificationBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Event::on(
            BookService::class,
            ServiceEntityEvent::AFTER_CREATE,
            [BookNotificationHandler::class, 'sendAfterCreateNotification']
        );
    }
}
