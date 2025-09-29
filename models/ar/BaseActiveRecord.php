<?php

namespace app\models\ar;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

abstract class BaseActiveRecord extends ActiveRecord
{
    /**
     * @var bool — применять фильтр active по умолчанию
     */
    protected const APPLY_ACTIVE_FILTER = false;

    public static function find(): ActiveQuery
    {
        $query = parent::find();

        if (static::shouldApplyActiveFilter()) {
            $query = static::applyActiveFilter($query);
        }

        return $query;
    }

    protected static function shouldApplyActiveFilter(): bool
    {
        if (!static::APPLY_ACTIVE_FILTER) {
            return false;
        }

        if (Yii::$app instanceof \yii\console\Application || Yii::$app->user->isGuest) {
            return true;
        }

        // Если явно разрешено показывать неактивные записи — фильтр не нужен
        return !(defined('UNACTIVE_RECORDS') && UNACTIVE_RECORDS);
    }

    protected static function applyActiveFilter(ActiveQuery $query): ActiveQuery
    {
        return $query->andWhere([static::tableName() . '.active' => 1]);
    }
}
