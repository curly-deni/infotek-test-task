<?php

namespace app\models\ar;

use yii\behaviors\TimestampBehavior;

/**
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $cover_image
 * @property boolean $active
 * @property int $created_at
 * @property int $updated_at
 *
 * @property-read AuthorAR[] $authors
 */
class BookAR extends BaseActiveRecord
{
    protected const APPLY_ACTIVE_FILTER = true;

    public $authorIds;

    public static function tableName(): string
    {
        return 'books';
    }

    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title', 'cover_image'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['isbn'], 'unique'],
            [['year'], 'integer'],
            [['description'], 'string'],
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

    public function getAuthors()
    {
        return $this->hasMany(AuthorAR::class, ['id' => 'author_id'])
            ->viaTable('book_authors', ['book_id' => 'id']);
    }

    public function linkAuthors(array $authorIds): void
    {
        foreach ($authorIds as $authorId) {
            $author = AuthorAR::findOne($authorId);
            if ($author) {
                $this->link('authors', $author);
            }
        }
    }
}
