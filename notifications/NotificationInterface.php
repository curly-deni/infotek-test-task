<?php

namespace app\notifications;

interface NotificationInterface
{
    public function send(string $phoneNumber, string $message): void;

    public function bulkSend(array $phoneNumbers, string $message): void;
}
