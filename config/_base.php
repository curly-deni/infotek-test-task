<?php

$params = require __DIR__ . '/_params.php';
$db = require __DIR__ . '/_db.php';

$config = [
    'id' => '',
    'name' => env('APP_NAME', 'My Application'),
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'queue',
        \app\bootstrap\ServiceBootstrap::class,
        \app\bootstrap\NotificationBootstrap::class
    ],
    'language' => env('YII_LANGUAGE', 'en-US'),
    'sourceLanguage' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'as log' => \yii\queue\LogBehavior::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => \app\models\adapters\UserIdentity::class,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => toBool(env('MAILER_USE_FILE_TRANSPORT', 'true')),
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    'container' => [
        'definitions' => [
            \app\notifications\NotificationInterface::class => \app\notifications\SmsNotification::class,
        ],
    ],
];

// dev-модули
if (YII_ENV_DEV) {

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'panels' => [
            'queue' => \yii\queue\debug\Panel::class,
        ],
        'allowedIPs' => ['0.0.0.0', '::/0', '172.26.0.1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['0.0.0.0', '::/0', '172.26.0.1'],
    ];
}

return $config;
