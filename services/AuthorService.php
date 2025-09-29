<?php

namespace app\services;

use app\models\ar\AuthorAR;
use yii\db\ActiveQuery;

class AuthorService extends AbstractEntityService
{
    public static function getEntityClass(): string
    {
        return AuthorAR::class;
    }

    public function topAuthorsByYear(int $year, bool $activeOnly = true, int $limit = 10): array
    {
        $query = AuthorAR::find()
            ->joinWith(['books' => function ($q) use ($year, $activeOnly) {
                $q->andWhere(['books.year' => $year]);
                if ($activeOnly) {
                    $q->andWhere(['books.active' => 1]);
                }
            }])
            ->select(['authors.*', 'COUNT(books.id) AS books_count'])
            ->groupBy('authors.id')
            ->orderBy(['books_count' => SORT_DESC])
            ->limit($limit);

        if ($activeOnly) {
            $query->andWhere(['authors.active' => 1]);
        }

        return $query->asArray()->all();
    }


}
