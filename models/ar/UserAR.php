<?php

namespace app\models\ar;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

class UserAR extends BaseActiveRecord
{
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_UPDATE = 'update';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;

    public ?string $password;

    protected const APPLY_ACTIVE_FILTER = true;

    public static function tableName()
    {
        return 'users';
    }

    public static function shouldApplyActiveFilter(): bool
    {
        if (!static::APPLY_ACTIVE_FILTER) {
            return false;
        }

        if (\Yii::$app instanceof \yii\console\Application) {
            return true;
        }

        return !(defined('UNACTIVE_RECORDS') && UNACTIVE_RECORDS);
    }

    protected static function applyActiveFilter(ActiveQuery $query): ActiveQuery
    {
        $query->andWhere([static::tableName() . '.status' => self::STATUS_ACTIVE]);

        return $query;
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTER] = [
            'first_name', 'last_name', 'email', 'username', 'password', 'status'
        ];
        $scenarios[self::SCENARIO_UPDATE] = [
            'first_name', 'last_name', 'email', 'username', 'password', 'status'
        ];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'username'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique'],
            ['username', 'unique'],
            ['email', 'string', 'max' => 255],
            ['username', 'string', 'min' => 3, 'max' => 50],
            ['password_hash', 'string', 'max' => 255],
            ['auth_key', 'string', 'max' => 255],
            ['password_reset_token', 'string', 'max' => 255],
            ['status', 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],

            ['password', 'required', 'on' => self::SCENARIO_REGISTER],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!empty($this->password)) {
                $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
            }
            if ($insert && empty($this->auth_key)) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    public function getName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function setName(string $name): void
    {
        $parts = explode(' ', trim($name), 2);
        $this->first_name = $parts[0] ?? '';
        $this->last_name = $parts[1] ?? '';
    }
}
