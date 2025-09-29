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
                        // все могут подписываться и проверять
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
        $author = $this->findAuthor($id);
        $userId = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
        $phone = Yii::$app->request->post()['DynamicModel']['phone'];

        try {
            $this->service->subscribeByPhone($author, $phone, $userId);
            Yii::$app->session->setFlash('success', 'You have successfully subscribed.');
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Subscription failed: ' . $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionUnsubscribe(int $id)
    {
        $author = $this->findAuthor($id);
        $phone = Yii::$app->request->post()['DynamicModel']['phone'];

        try {
            $this->service->unsubscribeByPhone($author, $phone);
            Yii::$app->session->setFlash('success', 'You have successfully unsubscribed.');
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Unsubscription failed: ' . $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionCheck(int $id)
    {
        $author = $this->findAuthor($id);
        $userId = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
        $phone = Yii::$app->request->post()['DynamicModel']['phone'];

        $isSubscribed = $this->service->isSubscribed($author, $phone, $userId);
        $message = $isSubscribed
            ? 'You are already subscribed to this author.'
            : 'You are not subscribed to this author.';

        Yii::$app->session->setFlash('info', $message);
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
