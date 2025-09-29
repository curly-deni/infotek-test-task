<?php

namespace app\controllers\entities;

use app\services\AbstractEntityService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

abstract class AbstractEntityController extends Controller
{
    protected AbstractEntityService $service;

    public function __construct($id, $module, $config = [])
    {
        $this->service = Yii::$container->get($this->getEntityServiceClass());
        parent::__construct($id, $module, $config);
    }

    abstract public static function getEntityServiceClass(): string;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ]);
    }

    public function actionIndex()
    {
        $entityClass = $this->service::getEntityClass();

        $dataProvider = new ActiveDataProvider([
            'query' => $entityClass::find(),
            'pagination' => ['pageSize' => 50],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView(int $id)
    {
        $model = $this->findModel($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $entityClass = $this->service::getEntityClass();

        if (Yii::$app->request->isPost) {
            $data = $this->loadAndModifyModel();
            $model = $this->service->create($data);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model = new $entityClass();
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $model = $this->loadAndModifyModel($model);
            $this->service->update($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);
        $this->service->delete($model);
        return $this->redirect(['index']);
    }

    protected function findModel(int $id)
    {
        $entityClass = $this->service::getEntityClass();
        $model = $entityClass::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        return $model;
    }

    private function loadAndModifyModel(?ActiveRecord $model = null): ActiveRecord|array
    {
        $entityClass = $this->service::getEntityClass();
        $postData = Yii::$app->request->post()[getObjectShortName($entityClass)] ?? [];

        if ($model === null) {
            return $this->modifyDataBeforeCreate($postData);
        }

        $model->load(Yii::$app->request->post());
        return $this->modifyDataBeforeUpdate($model);
    }

    protected function modifyDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function modifyDataBeforeUpdate(ActiveRecord $model): ActiveRecord
    {
        return $model;
    }
}
