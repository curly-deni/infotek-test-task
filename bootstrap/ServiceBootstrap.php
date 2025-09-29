<?php

namespace app\bootstrap;

use ReflectionClass;
use yii\base\BootstrapInterface;
use yii\helpers\FileHelper;

class ServiceBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $servicesPath = dirname(__DIR__) . '/services';
        $serviceFiles = FileHelper::findFiles($servicesPath, ['only' => ['*.php']]);

        foreach ($serviceFiles as $file) {
            $relative = str_replace($servicesPath . DIRECTORY_SEPARATOR, '', $file);
            $relative = str_replace('.php', '', $relative);
            $className = str_replace(DIRECTORY_SEPARATOR, '\\', $relative);
            $fqcn = "\\app\\services\\$className";

            if (class_exists($fqcn)) {
                $ref = new ReflectionClass($fqcn);
                if (!$ref->isAbstract()) {
                    $app->set($fqcn, $fqcn);
                }
            }
        }
    }
}
