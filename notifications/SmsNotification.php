<?php

namespace app\notifications;

use Yii;
use yii\httpclient\Client;

class SmsNotification implements NotificationInterface
{
    private string $apiUrl = 'https://smspilot.ru/api.php';
    private string $apiKey;
    private ?string $from;

    public function __construct()
    {
        $this->apiKey = Yii::$app->params['smsPilotApiKey'] ?? '';
        $this->from   = Yii::$app->params['smsPilotSenderId']   ?? null; // имя отправителя
    }

    public function send(string $phoneNumber, string $message): void
    {
        $this->bulkSend([$phoneNumber], $message);
    }

    public function bulkSend(array $phoneNumbers, string $message): void
    {
        if (empty($phoneNumbers)) {
            Yii::warning("bulkSend вызван с пустым списком номеров");
            return;
        }

        $numbers = array_map(function ($item) {
            if (is_array($item) && isset($item['id'])) {
                return $item['id'];
            }
            return (string)$item;
        }, $phoneNumbers);

        $to = implode(',', $numbers);

        $payload = [
            'send'   => $message,
            'to'     => $to,
            'apikey' => $this->apiKey,
            'format' => 'json',
        ];

        if ($this->from) {
            $payload['from'] = $this->from;
        }

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($this->apiUrl)
            ->setData($payload)
            ->send();

        if (!$response->isOk) {
            Yii::error("Ошибка при запросе к SMSPilot: HTTP " . $response->statusCode);
            return;
        }

        $data = $response->data;

        if (isset($data['error'])) {
            Yii::error("Ошибка SMSPilot: {$data['error']['code']} - {$data['error']['description_ru']}");
        } else {
            foreach ($data['send'] as $item) {
                $phone  = $item['phone'] ?? '';
                $status = $item['status'] ?? '';
                $price  = $item['price'] ?? '0';
                Yii::info("SMSPilot: отправлено на $phone, статус=$status, цена=$price");
            }
        }
    }
}
