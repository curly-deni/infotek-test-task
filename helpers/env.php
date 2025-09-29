<?php

use app\services\FileUploadService;

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('toBool')) {
    function toBool(mixed $value): bool
    {
        if (is_bool($value)) return $value;
        return in_array(strtolower((string)$value), ['1', 'true', 'yes'], true);
    }
}


if (!function_exists('saveUploadedFile')) {
    function saveUploadedFile(string $name, bool $multiple = false): string|array|null
    {
        $uploader = Yii::$container->get(FileUploadService::class);
        return $uploader->upload($name, $multiple);
    }
}
