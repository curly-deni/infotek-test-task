<?php

$base = require __DIR__ . '/_base.php';

$config = array_merge_recursive($base, [
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => null,
            'migrationNamespaces' => [
                'yii\queue\db\migrations',
            ],
        ],
    ],
    'aliases' => [
        '@tests' => '@app/tests',
    ]]);

return $config;
