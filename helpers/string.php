<?php

use yii\helpers\StringHelper;

if (!function_exists('getFullName')) {
    function getFullName(array $data): string
    {
        return implode(' ', array_filter([
            $data['first_name'] ?? null,
            $data['middle_name'] ?? null,
            $data['last_name'] ?? null,
        ]));
    }
}

if (!function_exists('getObjectShortName')) {
    function getObjectShortName(object|string $class): string
    {
        return StringHelper::basename(is_object($class) ? get_class($class) : $class);
    }
}
