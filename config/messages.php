<?php

return [
    // Каталог с исходниками для сканирования
    'sourcePath' => __DIR__ . '/../',

    // Каталог для хранения файлов переводов
    'messagePath' => __DIR__ . '/../messages',

    // Исходный язык проекта
    'sourceLanguage' => 'en-US',

    // Языки, для которых генерируем переводы
    'languages' => ['ru'],

    // Функция перевода
    'translator' => 'Yii::t',

    // Маски файлов для сканирования
    'phpFilePattern' => '*.php',

    // Категории сообщений
    'only' => ['*'],  // все категории

    // Исключаемые папки
    'except' => [
        '.docker',
        'vendor',
        'runtime',
        'tests',
        '.git',
        '.svn',
    ],

    // Дополнительно: игнорируемые файлы
    'ignoreFiles' => [
        '.svn', '.git', '.gitignore', '.gitkeep'
    ],

    // Формат файлов перевода
    'format' => 'php',

    'sort' => false,
    'removeOld' => false,
];
