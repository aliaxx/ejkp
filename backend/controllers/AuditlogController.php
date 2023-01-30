<?php

namespace backend\controllers;

use Yii;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

use common\utilities\ActionHandler;

use common\models\AuditLog;
use common\models\AuditLogSearch;

use yii\web\NotFoundHttpException;

class AuditLogController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => Yii::$app->access->can('audit-log-read'),
                        // 'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AuditLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuditLog model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // var_dump($id);
        // exit();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $actionHandler->execute();

            // Refresh model information
            $model = $this->findModel($id);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the AuditLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuditLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        // var_dump($id);
        if (($model = Auditlog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Halaman tidak dijumpai.');
        }
    }
}
