<?php

namespace app\models\ar;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $phone
 * @property int|null $user_id
 * @property boolean $active
 *
 * @property-read AuthorAR[] $authors
 * @property-read UserAR|null $user
 */
class SubscriberAR extends BaseActiveRecord
{
    protected const APPLY_ACTIVE_FILTER = true;

    public static function tableName(): string
    {
        return 'subscribers';
    }

    public function rules(): array
    {
        return [
            [['phone'], 'required'],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'unique'],
            [['user_id'], 'integer'],
            [['active'], 'boolean'],
            [['active'], 'default', 'value' => true],
            [['active'], 'required'],
        ];
    }

    public function getAuthors()
    {
        return $this->hasMany(AuthorAR::class, ['id' => 'author_id'])
            ->viaTable('author_subscribers', ['subscriber_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(UserAR::class, ['id' => 'user_id']);
    }
}
