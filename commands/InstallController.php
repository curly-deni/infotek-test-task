<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

class InstallController extends Controller
{

    public function actionIndex()
    {

        \Yii::$app->runAction('migrate/up', ['migrationPath' => '@vendor/dektrium/yii2-user/migrations', 'interactive' => false]);
        \Yii::$app->runAction('migrate/up', ['interactive' => false]);

        return ExitCode::OK;
    }
}
