<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;
use common\models\Peranan;
use backend\models\PerananSearch;
use common\models\PerananAkses;

use backend\components\LogActions;

/**
 * PerananController implements the CRUD actions for Peranan model.
 */
class PerananController extends Controller
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
                        'allow' => Yii::$app->access->can('peranan-read'),
                        'actions' => ['index', 'view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('peranan-write'),
                        'actions' => ['create', 'update', 'delete', 'access'],
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
     * Lists all Peranan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PerananSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-csv' => function ($model) use ($dataProvider) {
                    $rows = [];
                    $header = [
                        $model->getAttributeLabel('NAMAPERANAN'),
                        $model->getAttributeLabel('STATUS'),
                        $model->getAttributeLabel('PGNAKHIR'),
                        $model->getAttributeLabel('TRKHAKHIR'),
                    ];
                    $rows[] = $header;

                    $dataProvider->setPagination(false);
                    $models = $dataProvider->getModels();
                    foreach ($models as $model) {
                        $rows[] = [
                            $model->NAMAPERANAN, OptionHandler::resolve('STATUS', $model->STATUS),
                            $model->updatedByUser->NAMA, date('Y-m-d H:i:s', $model->TRKHAKHIR),
                        ];
                    }

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename='.Yii::$app->controller->id.'.csv');
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
                    $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
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
     * Displays a single Peranan model.
     * @param integer $id
     * @return mixed
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
     * Creates a new Peranan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Peranan();

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
     * Updates an existing Peranan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->IDPERANAN == 1) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

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
     * Deletes an existing Peranan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->IDPERANAN == 1) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

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
     * Finds the Peranan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Peranan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Peranan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Updates PerananAkses model.
     */
    public function actionAccess($id)
    {
        $parentModel = $this->findModel($id);
        if ($parentModel->IDPERANAN == 1) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        // old data for logging update action
        $oldmodel = json_encode($parentModel->getAttributes());
        
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($parentModel);
            $action = $actionHandler->execute();

            if ($selections = Yii::$app->request->post('chk')) {
                PerananAkses::deleteAll(['IDPERANAN' => $parentModel->IDPERANAN]);

                foreach ($selections as $code => $selection) {
                    $model = new PerananAkses();
                    $model->IDPERANAN = $parentModel->IDPERANAN;
                    $model->KODAKSES = $code;
                    $model->AKSES_URUS = (!empty($selection['write']))?1:0;

                    if ($model->AKSES_URUS) $model->AKSES_LIHAT = 1;
                    else $model->AKSES_LIHAT = (!empty($selection['read']))?1:0;

                    $model->save(false);
                }

                $log = new LogActions;
                $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                $actionHandler->gotoReturnUrl($model);
            }
        }

        return $this->render('access', [
            'parentModel' => $parentModel,
        ]);
    }
}
