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
use backend\modules\penyelenggaraan\models\PerkaraKomponenPrgn;
use backend\modules\penyelenggaraan\modules\penggredan\models\PerkaraKomponenPrgnSearch;

/**
 * ButirKesalahanController implements the CRUD actions for PerkaraKomponenPrgn model.
 */
class PerkaraKomponenPrgnController extends Controller
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
                        'allow' => Yii::$app->access->can('keterangan-read'),
                        'actions' => ['index', 'view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('keterangan-write'),
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
     * Lists all PerkaraKomponen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PerkaraKomponenPrgnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-csv' => function ($model) use ($dataProvider) {
                    $rows = [];
                    $header = [
                        $model->getAttributeLabel('JENIS'),
                        $model->getAttributeLabel('KODPERKARA'),
                        $model->getAttributeLabel('KODKOMPONEN'),
                        $model->getAttributeLabel('KODPRGN'),
                        $model->getAttributeLabel('PRGN'),
                        $model->getAttributeLabel('MARKAH'),
                        $model->getAttributeLabel('STATUS'),
                        $model->getAttributeLabel('PGNAKHIR'),
                        $model->getAttributeLabel('TRKHAKHIR'),
                    ];
                    $rows[] = $header;

                    $dataProvider->setPagination(false);
                    $models = $dataProvider->getModels();
                    foreach ($models as $model) {
                        $rows[] = [
                            $model->JENIS, $model->kesalahan->seksyen, $model->KODKOMPONEN, $model->KODPRGN, $model->PRGN, $model->MARKAH,
                            OptionHandler::resolve('STATUS', $model->STATUS),
                            $model->updatedByUser->NAMA, date('Y-m-d H:i:s', $model->TRKHAKHIR),
                        ];
                    }

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename='.Yii::$app->controller->id.'PerkaraKomponenPenerangan.csv');
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
                    $mpdf->Output(Yii::$app->controller->id.'PerkaraKomponenPenerangan.pdf', \Mpdf\Output\Destination::DOWNLOAD);
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
     * Displays a single KesalahanButir model.
     * @param string $JENIS
     * @param integer $KODPERKARA
     * @param integer $KODKOMPONEN
     * @return mixed
     */
    public function actionView($JENIS, $KODPERKARA, $KODKOMPONEN, $KODPRGN)
    {
        $model = $this->findModel($JENIS, $KODPERKARA, $KODKOMPONEN, $KODPRGN);

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
     * Creates a new KesalahanButir model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PerkaraKomponenPrgn();

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {
                // $count = PerkaraKomponen::find()->where(['JENIS' => $model->JENIS, 'KODPERKARA' => $model->KODPERKARA])->count();
                // $model->KODKOMPONEN = ($count + 1);

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
     * Updates an existing KesalahanButir model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $JENIS
     * @param integer $KODPERKARA
     * @param integer $KODKOMPONEN
     * @return mixed
     */
    public function actionUpdate($JENIS, $KODPERKARA, $KODKOMPONEN, $KODPRGN)
    {
        $model = $this->findModel($JENIS, $KODPERKARA, $KODKOMPONEN, $KODPRGN);

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
     * Deletes an existing KesalahanButir model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $JENIS
     * @param integer $KODPERKARA
     * @param integer $KODKOMPONEN
     * @return mixed
     */
    public function actionDelete($JENIS, $KODPERKARA, $KODKOMPONEN, $KODPRGN)
    {
        $model = $this->findModel($JENIS, $KODPERKARA, $KODKOMPONEN, $KODPRGN);
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
     * Finds the PerkaraKomponenPrgn model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $JENIS
     * @param integer $KODPERKARA
     * @param integer $KODKOMPONEN
     * @return PerkaraKomponenPrgn the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($JENIS, $KODPERKARA, $KODKOMPONEN, $KODPRGN)
    {
        if (($model = PerkaraKomponenPrgn::findOne(['JENIS' => $JENIS, 'KODPERKARA' => $KODPERKARA, 'KODKOMPONEN' => $KODKOMPONEN, 'KODPRGN' => $KODPRGN])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
