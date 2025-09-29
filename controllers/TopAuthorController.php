<?php

namespace app\controllers;

use app\notifications\NotificationInterface;
use app\services\AuthorService;
use app\services\BookService;
use yii\web\Controller;

class TopAuthorController extends Controller
{
    protected AuthorService $authorService;
    protected BookService $bookService;

    public function __construct($id, $module, AuthorService $authorService, BookService $bookService, $config = [])
    {
        $this->authorService = $authorService;
        $this->bookService = $bookService;

        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $years = $this->bookService->getAvailableYears();

        return $this->render('years', [
            'years' => $years,
        ]);
    }

    public function actionView(int $id)
    {
        $authors = $this->authorService->topAuthorsByYear($id);

        return $this->render('by-year', [
            'year' => $id,
            'authors' => $authors,
        ]);
    }
}
