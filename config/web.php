<?php

$base = require __DIR__ . '/_base.php';

$config = array_merge_recursive($base, [
    'components' => [
        'user' => [
            'enableAutoLogin' => true,
        ],
        'request' => [
            'cookieValidationKey' => env('COOKIE_VALIDATION_KEY', '00000000000000000000000000000000'),
            'enableCsrfValidation' => toBool(env('CSRF_VALIDATION', 'true')),
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<action:(login|logout)>' => 'site/<action>',

                '<controller:[\w\-]+>/<action:[\w\-]+>' => '<controller>/<action>',
                '<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>' => '<controller>/<action>',
            ],
        ],

    ],
]);

return $config;
