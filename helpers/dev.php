<?php

use yii\helpers\VarDumper;

if (!function_exists('dump')) {
    function dump(...$args): void
    {
        echo '<pre>';
        VarDumper::dump($args, 10, true);
        echo '</pre>';
    }
}

if (!function_exists('dd')) {
    function dd(...$args): void
    {
        dump(...$args);
        die();
    }
}
