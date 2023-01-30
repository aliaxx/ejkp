<?php

namespace backend\modules\peniaga\controllers;

use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanMainSearch;
use backend\modules\peniaga\models\Transgerai;
use backend\modules\peniaga\models\TransgeraiSearch;
use backend\modules\integrasi\models\Sewa;
use backend\modules\lawatanmain\models\LawatanPemilik;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


use Yii;
use yii\filters\AccessControl;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use backend\components\LogActions;
use common\models\User;
use common\models\Pengguna;
use common\utilities\ActionHandler;
use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;

/**
 * KawalanPerniagaanController implements the CRUD actions for KawalanPerniagaan model.
 */
class KawalanPerniagaanController extends Controller
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
                        'allow' => Yii::$app->access->can('KPN-read'),
                        'actions' => ['index', 'view', 'lokasi-am', 'gerai', 'get-maklumat-sewa', 'gerai-view', 'get-gerai-delete','gambar'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('KPN-write'),
                        'actions' => ['create', 'update', 'delete', 'lokasi-am','gerai'],
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
     * Lists all Kawalan Perniagaan data in LawatanMain models.
     * Filter Kawalan Perniagaan data.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LawatanMainSearch();
        $dataProvider = $searchModel->search('KPN',$this->request->queryParams);
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                
                'export-pdf' => function ($model) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('_print-grid', ['dataProvider' => $dataProvider]));
                    $mpdf->Output(Yii::$app->controller->id . ' KawalanPerniagaan.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                },
            ]);
            $actionHandler->execute();
        }
        
        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'KPN',
            'title' => 'Pemantauan Penjaja',
            'breadCrumbs' => ['Peniaga Kecil & Penjaja', 'Pemantauan Penjaja'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Display data Kawalan Perniagaan based on id.
     * @param string $id Nosiri
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

        return $this->render('@backend/modules/lawatanmain/views/view', [
            'idModule' => 'KPN',
            'title' => 'Pemantauan Penjaja',
            'breadCrumbs' => ['Peniaga Kecil & Penjaja', ['label' => 'Pemantauan Penjaja', 'url' => ['index']], $model->NOSIRI, 'Paparan Maklumat'],
            'model' => $model,
        ]);
    }


    /**
     * Create new record for Kawalan Perniagaan and save to model LawatanMain.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LawatanMain();

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();
    
            if ($action && $model->load(Yii::$app->request->post())) {

                $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
                $model->IDZON_AM = $idzon_amarr[0];
                $model->PRGNLOKASI_AM = $idzon_amarr[1];
                $model->setNosiri('KPN'); //set No Siri.
                $model->IDSUBUNIT = Yii::$app->user->identity->SUBUNIT;
                
                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);

                $model->PGNDAFTAR = Yii::$app->user->ID;
                $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));

                if ($model->save(false)) {

                    $model->saveAhliPasukan(); //save maklumat pasukan

                    // record log
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_CREATE, $model);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }

        return $this->render('@backend/modules/lawatanmain/views/create', [
            'idModule' => 'KPN',
            'title' => 'Pemantauan Penjaja',
            'titleMain' => 'Peniaga Kecil & Penjaja',
            'model' => $model,
        ]);
        
    }


    /**
     * Update an existing Kawalan Perniagaan data.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param string $id Nosiri
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $model = $this->findModel($id);

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($model->ketuapasukan0){
                $model->KETUAPASUKAN = $model->ketuapasukan0->IDPENGGUNA;
            }
            
            // old data for logging update action
            $oldmodel = json_encode($model->getAttributes());

            if ($action && $model->load(Yii::$app->request->post())) {

                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);

                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));

                if ($model->save(false)) {

                    $model->saveAhliPasukan(); //save maklumat pasukan
                    
                    // record log
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }

        return $this->render('@backend/modules/lawatanmain/views/update', [
            'idModule' => 'KPN',
            'title' => 'Pemantauan Penjaja',
            'titleMain' => 'Peniaga Kecil & Penjaja',
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Kawalan Perniagaan data.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id Nosiri
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
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
     * Finds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id Nosiri
     * @return LawatanMain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LawatanMain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Tabmenu Kawalan Perniagaan.
     * Redirect to Gerai/Petak index page.
     * Save and update record.
     * @param $nosiri
     */
    public function actionGerai($nosiri, $ID = null) //alia29092022
    {
        $model = $this->findModel($nosiri);

        /* for auditlog */
        $log = new LogActions;
        $tindakan = $log::ACTION_CREATE;
        $oldmodel = null;
        /* */
          
        $searchModel = new TransgeraiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $modelGerai = new Transgerai();
        $modelGerai->NOSIRI = $model->NOSIRI;
        
        //for update data
        if ($ID) { //id trans_gerai

            $modelGerai = Transgerai::findOne(['ID' => $ID]);
            $flag['newRecord'] = false;

            if ($modelGerai->TRKHLAWATAN_GERAI) {
                $modelGerai->TRKHLAWATAN_GERAI = date('d/m/Y', strtotime($modelGerai->TRKHLAWATAN_GERAI));
            }

            /* for auditlog */
            $tindakan = $log::ACTION_UPDATE;
            $oldmodel = json_encode($modelGerai->getAttributes());
            /* */
        }
        
        if ($modelGerai->load(Yii::$app->request->post())&& $modelGerai->validate()) {

            $modelGerai->saveMaklumatPemilikGerai();
            
            if ($flag['newRecord']) {
                $modelGerai->IDPEMILIK=$modelGerai->pemilikSewa0->ID;
            }

            if ($modelGerai->TRKHLAWATAN_GERAI) {
                $modelGerai->TRKHLAWATAN_GERAI = DateTimeHelper::convert($model->TRKHMULA . ', 12:00 PG');
            }

            $modelGerai->PGNDAFTAR = Yii::$app->user->id;
            $modelGerai->TRKHDAFTAR = strtotime(date("Y-m-d H:i:s"));
            $modelGerai->PGNAKHIR = Yii::$app->user->id;
            $modelGerai->TRKHAKHIR = strtotime(date("Y-m-d H:i:s"));
            
            if ($modelGerai->save()) {

                if ($flag['newRecord'])
                    Yii::$app->session->setFlash('success', 'Berjaya menambah rekod Gerai.');
                else
                    Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod Gerai.');
                return $this->redirect(['gerai', 'nosiri' => $model->NOSIRI]);
            }
        }

        return $this->render('extra/gerai', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelGerai' => $modelGerai,
        ]);
    }


    /**
     * Tabmenu Kawalan Perniagaan.
     * Redirect to Gerai/Petak view page.
     * View data.
     * @param $nosiri
     */
    public function actionGeraiView($nosiri, $ID)
    {
        $model = Transgerai::findOne(['ID' => $ID]);

        return $this->render('extra/gerai-view', [
            'model' => $model,
        ]);
    }


    /**
     * Tabmenu Kawalan Perniagaan.
     * Redirect to Gerai/Petak index page.
     * Delete data.
     * @param $nosiri
     */
    public function actionGetGeraiDelete()
    {
        if ($post = Yii::$app->request->post()) {
            
            $modelGerai = Transgerai::findOne(['NOSIRI' => $post['nosiri'], 'ID' => $post['ID']]);
            LawatanPemilik::deleteAll(['NOSIRI' => $post['nosiri'], 'ID' => $modelGerai->IDPEMILIK]);
            if ($modelGerai) {
                $modelGerai->delete();
                // record log
                $log = new LogActions;
                $log->recordLog($log::ACTION_DELETE, $modelGerai);

                Yii::$app->session->setFlash('success', 'Berjaya menghapuskan rekod Gerai.');
                return $this->redirect(['gerai', 'nosiri' => $modelGerai->NOSIRI]);
            }
        }
    }


    /**
    * Tabmenu Kawalan Perniagaan.
    * Use in form gerai where it will autofill the textbox after choose 'NO GERAI'.
    * Get data from model Sewa to autofill the textbox.
    */
    public function actionGetMaklumatSewa($lotno = null) //$lot no is object that declare at js script
    {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (!empty($lotno)) {
                $query = Sewa::find()->where(['LOT_NO' => $lotno]);
                $source = $query->one();

                if (!empty($source)) {
                    $output['results'] = [
                        'ACCOUNT_NUMBER' => $source->ACCOUNT_NUMBER,
                        'LICENSE_NUMBER' => $source->LICENSE_NUMBER,
                        'NAME'=> $source->NAME,
                        'ADDRESS_1' => $source->ADDRESS_1,
                        'ADDRESS_2' => $source->ADDRESS_2,
                        'ADDRESS_3' => $source->ADDRESS_3,
                        'ADDRESS_POSTCODE' => $source->ADDRESS_POSTCODE,
                        'LOT_NO' => $source->LOT_NO,
                        'LOCATION_ID' => $source->LOCATION_ID,
                        'LOCATION_NAME' => $source->LOCATION_NAME,
                        'RENT_CATEGORY' => $source->RENT_CATEGORY,
                        'SALES_TYPE' => $source->SALES_TYPE,
                        'ASSET_ADDRESS_1' => $source->ASSET_ADDRESS_1,
                        'ASSET_ADDRESS_2' => $source->ASSET_ADDRESS_2,
                        'ASSET_ADDRESS_3' => $source->ASSET_ADDRESS_3,
                        'ASSET_ADDRESS_POSTCODE' => $source->ASSET_ADDRESS_POSTCODE,
                        'ASSET_ADDRESS_LAT' => $source->ASSET_ADDRESS_LAT,
                        'ASSET_ADDRESS_LONG' => $source->ASSET_ADDRESS_LONG,
                        'RENT_AMOUNT' => $source->RENT_AMOUNT,
                        'OUTSTANDING_RENT_AMOUNT' => $source->OUTSTANDING_RENT_AMOUNT,
                    ];
                }
                return $output;
            }    
    }

    
    /**
     * Tabmenu Kawalan Perniagaan.
     * Redirect to page Gambar at LawatanMain.
    */
    public function actionGambar($nosiri)
    {
        $model = $this->findModel($nosiri);

        return $this->render('@backend/modules/lawatanmain/views/gambar', [
            'idModule' => 'KPN',
            'title' => 'Pemantauan Penjaja',
            'titleMain' => 'Peniaga Kecil & Penjaja',
            'model' => $model,
        ]);
    }

    
}
