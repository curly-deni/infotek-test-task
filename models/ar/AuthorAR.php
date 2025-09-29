<?php

namespace app\models\ar;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_name
 * @property boolean $active
 * @property int $created_at
 * @property int $updated_at
 *
 * @property-read BookAR[] $books
 * @property-read SubscriberAR[] $subscribers
 */
class AuthorAR extends BaseActiveRecord
{
    protected const APPLY_ACTIVE_FILTER = true;

    public static function tableName(): string
    {
        return 'authors';
    }

    public function rules(): array
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 100],
            [['active'], 'boolean'],
            [['active'], 'default', 'value' => true],
            [['active'], 'required'],
        ];
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getFullName(): string
    {
        return getFullName($this->attributes);
    }

    public function getBooks()
    {
        return $this->hasMany(BookAR::class, ['id' => 'book_id'])
            ->viaTable('book_authors', ['author_id' => 'id']);
    }

    public function getSubscribers()
    {
        return $this->hasMany(SubscriberAR::class, ['id' => 'subscriber_id'])
            ->viaTable('author_subscribers', ['author_id' => 'id']);
    }

    public function linkSubscriber(SubscriberAR $subscriber)
    {
        $this->link('subscribers', $subscriber);
    }
}
