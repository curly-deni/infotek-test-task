<?php

namespace app\controllers\entities;

use app\services\AuthorService;

class AuthorController extends AbstractEntityController
{
    public static function getEntityServiceClass(): string
    {
        return AuthorService::class;
    }
}
