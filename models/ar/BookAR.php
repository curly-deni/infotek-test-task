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

    public function linkAuthor(AuthorAR|int $author): void
    {

        $authorId = $author instanceof AuthorAR ? $author->id : $author;

        $exists = (new \yii\db\Query())
            ->from('book_authors')
            ->where(['book_id' => $this->id, 'author_id' => $authorId])
            ->exists();

        if (!$exists) {
            static::getDb()->createCommand()
                ->insert('book_authors', [
                    'book_id' => $this->id,
                    'author_id' => $authorId,
                ])->execute();
        }
    }

    public function unlinkAuthor(AuthorAR|int $author): void
    {

        $authorId = $author instanceof AuthorAR ? $author->id : $author;

        static::getDb()->createCommand()
            ->delete('book_authors', [
                'book_id' => $this->id,
                'author_id' => $authorId,
            ])->execute();
    }


    public function linkAuthors(array $authorIds): void
    {
        $authorIds = array_unique($authorIds);

        $existingIds = (new \yii\db\Query())
            ->select('author_id')
            ->from('book_authors')
            ->where(['book_id' => $this->id])
            ->column();

        $toAdd = array_diff($authorIds, $existingIds);
        $toRemove = array_diff($existingIds, $authorIds);

        $db = static::getDb();

        if (!empty($toAdd)) {
            $rows = array_map(fn($authorId) => ['book_id' => $this->id, 'author_id' => $authorId], $toAdd);
            $db->createCommand()->batchInsert('book_authors', ['book_id', 'author_id'], $rows)->execute();
        }

        if (!empty($toRemove)) {
            $db->createCommand()
                ->delete('book_authors', ['book_id' => $this->id, 'author_id' => $toRemove])
                ->execute();
        }
    }
}
