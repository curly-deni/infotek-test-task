<?php

namespace app\controllers;

use app\models\ar\AuthorAR;
use app\services\SubscriberService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AuthorSubscriptionController extends Controller
{
    public SubscriberService $service;

    public function __construct($id, $module, SubscriberService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'check', 'subscribe', 'unsubscribe'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'subscribe' => ['POST'],
                    'unsubscribe' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AuthorAR::find()->orderBy(['id' => SORT_DESC]),
            'pagination' => ['pageSize' => 50],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView(int $id)
    {
        $author = $this->findAuthor($id);
        return $this->render('view', ['model' => $author]);
    }

    public function actionSubscribe(int $id)
    {
        return $this->handleSubscriptionAction($id, function($author, $phone, $userId) {
            $this->service->subscribeByPhone($author, $phone, $userId);
            return 'You have successfully subscribed.';
        });
    }

    public function actionUnsubscribe(int $id)
    {
        return $this->handleSubscriptionAction($id, function($author, $phone) {
            $this->service->unsubscribeByPhone($author, $phone);
            return 'You have successfully unsubscribed.';
        });
    }

    public function actionCheck(int $id)
    {
        return $this->handleSubscriptionAction($id, function($author, $phone, $userId) {
            $isSubscribed = $this->service->isSubscribed($author, $phone, $userId);
            return $isSubscribed
                ? 'You are already subscribed to this author.'
                : 'You are not subscribed to this author.';
        }, 'info');
    }

    private function handleSubscriptionAction(int $id, callable $callback, string $flashType = 'success')
    {
        $author = $this->findAuthor($id);
        $userId = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
        $phone = Yii::$app->request->post('DynamicModel')['phone'] ?? null;

        try {
            $message = $callback($author, $phone, $userId);
            Yii::$app->session->setFlash($flashType, $message);
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Operation failed: ' . $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    protected function findAuthor(int $id): AuthorAR
    {
        $author = AuthorAR::findOne($id);
        if (!$author) {
            throw new NotFoundHttpException('Author not found.');
        }
        return $author;
    }
}
