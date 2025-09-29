<?php

namespace app\models\ar;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

abstract class BaseActiveRecord extends ActiveRecord
{
    protected const APPLY_ACTIVE_FILTER = false;

    public static function find(): ActiveQuery
    {
        $query = parent::find();

        if (static::shouldApplyActiveFilter()) {
            $query = static::applyActiveFilter($query);
        }

        return $query;
    }

    public static function shouldApplyActiveFilter(): bool
    {
        if (!static::APPLY_ACTIVE_FILTER) {
            return false;
        }

        if (Yii::$app instanceof \yii\console\Application) {
            return true;
        }

        if (Yii::$app->user->isGuest) {
            return true;
        }

        return !(defined('UNACTIVE_RECORDS') && UNACTIVE_RECORDS);
    }

    protected static function applyActiveFilter(ActiveQuery $query): ActiveQuery
    {
        return $query->andWhere([static::tableName() . '.active' => 1]);
    }
}
