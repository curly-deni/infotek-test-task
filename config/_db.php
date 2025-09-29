<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => env('DB_TYPE', 'mysql') .
        ':host=' . env('DB_HOST', 'localhost') .
        ';port=' . env('DB_PORT', '3306') .
        ';dbname=' . env('DB_NAME', 'yii2basic'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => env('DB_CHARSET', 'utf8'),

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
