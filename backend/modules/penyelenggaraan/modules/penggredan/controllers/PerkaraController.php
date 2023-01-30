<?php

namespace backend\modules\penyelenggaraan\modules\penggredan\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;

use backend\components\LogActions;
use backend\modules\penyelenggaraan\models\Perkara;
use backend\modules\penyelenggaraan\modules\penggredan\models\PerkaraSearch;
use yii\web\Response;

/**
 * KategoriController implements the CRUD actions for Perkara model.
 */
class PerkaraController extends Controller
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
                        'allow' => Yii::$app->access->can('perkara-read'),
                        'actions' => ['index', 'view', 'api'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('perkara-write'),
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
     * Lists all AsetKategori models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PerkaraSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-csv' => function ($model) use ($dataProvider) {
                    $rows = [];
                    $header = [
                        $model->getAttributeLabel('KODPERKARA'),
                        $model->getAttributeLabel('JENIS'),
                        $model->getAttributeLabel('PRGN'),
                        $model->getAttributeLabel('STATUS'),
                        // $model->getAttributeLabel('PDNDAFTAR'),
                        // $model->getAttributeLabel('TRKHDAFTAR'),
                        $model->getAttributeLabel('PGNAKHIR'),
                        $model->getAttributeLabel('TRKHAKHIR'),
                    ];
                    $rows[] = $header;

                    $dataProvider->setPagination(false);
                    $models = $dataProvider->getModels();
                    foreach ($models as $model) {
                        $rows[] = [
                            $model->KODPERKARA, $model->JENIS, $model->PRGN,
                            OptionHandler::resolve('STATUS', $model->STATUS),
                            //$model->createdByUser->NAMA, date('Y-m-d H:i:s', $model->TRKHDAFTAR),
                            $model->updatedByUser->NAMA, date('Y-m-d H:i:s', $model->TRKHAKHIR),
                        ];
                    }

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename='.Yii::$app->controller->id.'Perkara.csv');
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
                    $mpdf->Output(Yii::$app->controller->id.'Perkara.pdf', \Mpdf\Output\Destination::DOWNLOAD);
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
     * Displays a single AsetKategori model.
     * @param string $JENIS, $KODPERKARA
     * @return mixed
     */
    public function actionView($JENIS, $KODPERKARA)
    {
        $model = $this->findModel($JENIS, $KODPERKARA);

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $actionHandler->execute();

            // Refresh model information
            $model = $this->findModel($JENIS, $KODPERKARA);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new AsetKategori model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Perkara();

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {
                $model->STATUS = OptionHandler::STATUS_AKTIF;

                if ($model->save()) {
                    
                    //insert pendaftar dan pendaftar terkini to table perkara
                    $model->PGNDAFTAR = Yii::$app->user->ID;
                    $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                    $model->PGNAKHIR = Yii::$app->user->ID;
                    $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));
                    $model->save(false);
                     
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
     * Updates an existing AsetKategori model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $JENIS, $KODPERKARA
     * @return mixed
     */
    public function actionUpdate($JENIS, $KODPERKARA)
    {
        $model = $this->findModel($JENIS, $KODPERKARA);

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
     * Deletes an existing AsetKategori model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $JENIS, $KODPERKARA
     * @return mixed
     */
    public function actionDelete($JENIS, $KODPERKARA)
    {
        $model = $this->findModel($JENIS, $KODPERKARA);
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
     * Finds the Perkara model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $JENIS, $KODPERKARA
     * @return Perkara the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * at path url view, update, delete. Example: view?KODPERKARA=A&JENIS=1 (called 2 PK)
     */
    protected function findModel($JENIS, $KODPERKARA)
    {
        if (($model = Perkara::findOne(['JENIS' => $JENIS, 'KODPERKARA' => $KODPERKARA])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
