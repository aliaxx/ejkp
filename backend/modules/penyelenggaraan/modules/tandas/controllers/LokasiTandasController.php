<?php

namespace backend\modules\penyelenggaraan\modules\tandas\controllers;

use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\modules\penyelenggaraan\models\LokasiTandasvektor;
use backend\modules\penyelenggaraan\modules\tandas\models\LokasiTandasvektorSearch;
use backend\components\LogActions;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;

/**
 * LokasiTandasController implements the CRUD actions for LokasiTandasvektor model.
 */
class LokasiTandasController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all LokasiTandasvektor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LokasiTandasvektorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

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
     * Displays a single LokasiTandasvektor model.
     * @param int $ID ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
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
     * Creates a new LokasiTandasvektor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LokasiTandasvektor();

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {

                if ($model->save()) {    
                    
                     // record log
                     $log = new LogActions;
                     $log->recordLog($log::ACTION_CREATE, $model);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LokasiTandasvektor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $ID ID
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
     * Deletes an existing LokasiTandasvektor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $ID ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($ID)
    {
        // $this->findModel($ID)->delete();

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
     * Finds the LokasiTandasvektor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $ID ID
     * @return LokasiTandasvektor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LokasiTandasvektor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
