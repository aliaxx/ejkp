<?php

namespace backend\modules\penyelenggaraan\modules\param\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;

use backend\components\LogActions;
use backend\modules\penyelenggaraan\models\ParamDetail;
use backend\modules\penyelenggaraan\modules\param\models\ParamDetailSearch;

/**
 * ParamDetailController implements the CRUD actions for ParamDetail model.
 */
class ParamDetailController extends Controller
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
                        'allow' => Yii::$app->access->can('param-detail-read'),
                        'actions' => ['index', 'view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('param-detail-write'),
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
     * Lists all ParamDetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParamDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-csv' => function ($model) use ($dataProvider) {
                    $rows = [];
                    $header = [
                        $model->getAttributeLabel('KODJENIS'),
                        $model->getAttributeLabel('KODDETAIL'),
                        $model->getAttributeLabel('PRGN'),
                        $model->getAttributeLabel('STATUS'),
                        $model->getAttributeLabel('PGNAKHIR'),
                        $model->getAttributeLabel('TRKHAKHIR'),
                    ];
                    $rows[] = $header;

                    $dataProvider->setPagination(false);
                    $models = $dataProvider->getModels();
                    foreach ($models as $model) {
                        $rows[] = [
                            $model->KODJENIS->PRGN, $model->KODDETAIL, $model->PRGN,
                            OptionHandler::resolve('STATUS', $model->STATUS),
                            $model->updatedByUser->NAMA, date('Y-m-d H:i:s', $model->TRKHAKHIR),
                        ];
                    }

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename='.Yii::$app->controller->id.'Parameter Detail.csv');
                    $fp = fopen('php://output', 'w');
                    foreach ($rows as $row) {
                        fputcsv($fp, $row);
                    }
                    fclose($fp);
                    exit();
                },
                'export-pdf' => function ($model) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('_print', ['dataProvider' => $dataProvider]));
                    $mpdf->Output(Yii::$app->controller->id.'Parameter Detail.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                },
            ]);
            $actionHandler->execute();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ParamDetail model.
     * @param integer $kodtujuan
     * @param integer $kodasas
     * @return mixed
     */
    public function actionView($KODJENIS, $KODDETAIL)
    {
        $model = $this->findModel($KODJENIS, $KODDETAIL);

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
     * Creates a new ParamDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ParamDetail();

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {
                $count = ParamDetail::find()->where(['KODJENIS' => $model->KODJENIS])->count();
                $model->KODDETAIL = ($count + 1);

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
     * Updates an existing ParamDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $KODJENIS
     * @param integer $kodasas
     * @return mixed
     */
    public function actionUpdate($KODJENIS, $KODDETAIL)
    {
        $model = $this->findModel($KODJENIS, $KODDETAIL);

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            // old data for logging update action
            // $oldmodel = json_encode($model->getAttributes());

            if ($action && $model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    // record log
                    $log = new LogActions;
                    $oldmodel = json_encode($model->getAttributes());
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
     * Deletes an existing ParamDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $KODJENIS
     * @param integer $KODDETAIL
     * @return mixed
     */
    public function actionDelete($KODJENIS, $KODDETAIL)
    {
        $model = $this->findModel($KODJENIS, $KODDETAIL);
        if ($model->STATUS == OptionHandler::STATUS_TIDAK_AKTIF) {
            $model->STATUS = OptionHandler::STATUS_AKTIF;
        } else {
            $model->STATUS = OptionHandler::STATUS_TIDAK_AKTIF;
        }

        if ($model->save(false)) {
            // record log
            $log = new LogActions;
            $log->recordLog($log::ACTION_DELETE, $model);

            Yii::$app->session->setFlash('success', 'STATUS rekod telah berjaya ditukar.');

            $actionHandler = new ActionHandler($model);
            $actionHandler->gotoReturnUrl($model);
        }
    }

    /**
     * Finds the ParamDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $KODJENIS
     * @param integer $KODDETAIL
     * @return ParamDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($KODJENIS, $KODDETAIL)
    {
        if (($model = ParamDetail::findOne(['KODJENIS' => $KODJENIS, 'KODDETAIL' => $KODDETAIL])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
