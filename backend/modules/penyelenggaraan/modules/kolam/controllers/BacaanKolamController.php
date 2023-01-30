<?php

namespace backend\modules\penyelenggaraan\modules\kolam\controllers;

use yii;
use backend\modules\penyelenggaraan\models\BacaanKolam;
use backend\modules\penyelenggaraan\modules\kolam\models\BacaanKolamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;

/**
 * BacaanKolamController implements the CRUD actions for BacaanKolam model.
 */
class BacaanKolamController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => Yii::$app->access->can('kolam-read'),
                        'actions' => ['index', 'view', 'api'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('kolam-write'),
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all BacaanKolam models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BacaanKolamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        ActionHandler::setReturnUrl();
        //to render daftar button in index page -> go to the form
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel);
            $actionHandler->execute();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BacaanKolam model.
     * @param string $ID ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // var_dump("test");
        // exit();
        $model = $this->findModel($id);

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
     * Creates a new BacaanKolam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BacaanKolam();

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {
                //save record into each fields
                // $model->STATUS = OptionHandler::STATUS_AKTIF;
                // $model->PGNDAFTAR = Yii::$app->user->ID;
                // $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                // $model->PGNAKHIR = Yii::$app->user->ID;
                // $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));

                if ($model->save()) {     
                    
                     // record log
                     $log = new LogActions;
                     $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
                // print_r($model->errors);
                // exit;
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BacaanKolam model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $ID ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            // old data for logging update action
            $oldmodel = json_encode($model->getAttributes());

            if ($action && $model->load(Yii::$app->request->post())) {

                if ($model->save()) {

                    // record log
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BacaanKolam model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $ID ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($ID)
    {
        //id here referring to id in button status in file index
        $model = $this->findModel($ID);
        if ($model->STATUS == OptionHandler::STATUS_TIDAK_AKTIF) {
            $model->STATUS = OptionHandler::STATUS_AKTIF;
        } else {
            $model->STATUS = OptionHandler::STATUS_TIDAK_AKTIF;
        }

        if ($model->save(false)) {
            // record log
            $log = new LogActions;
            $log->recordLog($log::ACTION_DELETE, $model);

            Yii::$app->session->setFlash('success', 'Status rekod telah berjaya ditukar.');

            $actionHandler = new ActionHandler($model);
            $actionHandler->gotoReturnUrl($model);
        }
    }

    /**
     * Finds the BacaanKolam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $ID ID
     * @return BacaanKolam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BacaanKolam::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
