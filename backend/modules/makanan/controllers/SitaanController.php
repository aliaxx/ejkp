<?php

namespace backend\modules\makanan\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
// use \yii\web\IdentityInterface;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\Url;

use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanMainSearch;
use backend\modules\makanan\models\BarangSitaanSearch;
use backend\modules\makanan\models\BarangSitaan;
use backend\components\LogActions;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;


/**
 * SitaanController implements the CRUD actions for Sitaan model.
 */
class SitaanController extends Controller
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
                        'allow' => Yii::$app->access->can('SDR-read'),
                        'actions' => ['index', 'view', 'sitaan', 'sitaan-view', 'get-ahlis'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('SDR-write'),
                        'actions' => ['create', 'update', 'delete', 'sitaan', 'sitaan-view', 'sitaan-delete', 'file-upload', 'file-delete'],
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
     * Lists all Sitaan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LawatanMainSearch();
        $dataProvider = $searchModel->search('SDR', $this->request->queryParams);

        ActionHandler::setReturnUrl();
        //to render daftar button in index page -> go to the form
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel);
            $actionHandler->execute();
        }

        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'SDR',
            'title' => 'Sitaan',
            'breadCrumbs' => ['Mutu Makanan', 'Sitaan'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sitaan model.
     * @param string $NOSIRI Nosiri
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
            'idModule' => 'SDR',
            'title' => 'Sitaan',
            'breadCrumbs' => ['Mutu Makanan', ['label' => 'Sitaan', 'url' => ['index']], $model->NOSIRI, 'Paparan Maklumat'],
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Sitaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LawatanMain();

        if($model){
        // if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();
            
            if ($model->load(Yii::$app->request->post())) {
            // if ($action && $model->load(Yii::$app->request->post())) {

                $model->setNosiri($model->IDMODULE);  //set No Siri. //zihan
                $model->saveMaklumatPemilik(); //save maklumat lesen
                $model->saveAhliPasukan(); //save maklumat pasukan
              
                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);
                
                $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
                $model->IDZON_AM = $idzon_amarr[0];
                $model->PRGNLOKASI_AM = $idzon_amarr[1];
                $model->IDSUBUNIT = Yii::$app->user->identity->SUBUNIT;                
                
                // $model->PGNDAFTAR = Yii::$app->user->ID;
                // $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                // $model->PGNAKHIR = Yii::$app->user->ID;
                // $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));

                if ($model->save(false)) {

                    // record log
                    // $log = new LogActions;
                    // $log->recordLog($log::ACTION_CREATE, $model);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }

        // return $this->render('create', [
        return $this->render('@backend/modules/lawatanmain/views/create', [
            'idModule' => 'SDR',
            'titleMain' => 'Mutu Makanan',
            'title' => 'Sitaan',
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Sitaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $NOSIRI Nosiri
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if($model){
        // if (Yii::$app->request->post('action')) {
           
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($model->ketuapasukan0){
                $model->KETUAPASUKAN = $model->ketuapasukan0->IDPENGGUNA;
            }

            // old data for logging update action
            $oldmodel = json_encode($model->getAttributes());
            
            if ($model->load(Yii::$app->request->post())) {
            // if ($action && $model->load(Yii::$app->request->post())){

                $model->saveMaklumatPemilik(); //save maklumat lesen
                $model->saveAhliPasukan(); //save maklumat pasukan

                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);

                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));
                
                if ($model->save(false)) {
                    // record log
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }

        return $this->render('@backend/modules/lawatanmain/views/update', [
            'idModule' => 'SDR',
            'titleMain' => 'Mutu Makanan',
            'title' => 'Sitaan',
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sitaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $NOSIRI Nosiri
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
     * Finds the LawatanMain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $NOSIRI Nosiri
     * @return LawatanMain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LawatanMain::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSitaan($nosiri, $idbarang= null)
    {
        $model = $this->findModel($nosiri);
        /* for auditlog */
        // $log = new LogActions;
        // $tindakan = $log::ACTION_CREATE;
        // $oldmodel = null;
        /* */        

        $searchModel = new BarangSitaanSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $sitaan = new BarangSitaan();
        $sitaan->NOSIRI = $model->NOSIRI;

        if ($idbarang) {
            $sitaan = BarangSitaan::findOne(['ID' => $idbarang]);
            $flag['newRecord'] = false;

            /* for auditlog */
            // $tindakan = $log::ACTION_UPDATE;
            // $oldmodel = json_encode($sampel->getAttributes());
            /* */            
        }

        // start print list function - nur190521
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-notis' => function ($sitaan) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    // var_dump($sampel);
                    // exit();

                    $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
                    $mpdf->WriteHTML($this->render('_notis', ['dataProvider' => $dataProvider]));
                    // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                    $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
                },
                'export-persetujuan' => function ($sitaan) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
                    $mpdf->WriteHTML($this->render('_persetujuan', ['dataProvider' => $dataProvider]));
                    // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                    $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
                },
                'export-senarai' => function ($sitaan) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
                    $mpdf->WriteHTML($this->render('_senarai', ['dataProvider' => $dataProvider]));
                    // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                    $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
                },
            ]);

            $actionHandler->execute();
        }
        // end print list function

        if ($sitaan->load(Yii::$app->request->post()) && $sitaan) {

            // if($sitaan->isNewRecord){
            //     $sitaan->ID = Yii::$app->db->createCommand("UPDATE TBSITAAN SET ID = ROWNUM")->execute();  //update sequence of ID -NOR14102022   

            //     $count = BarangSitaan::find()->count();
            //     $sitaan->ID = ($count + 1);    
            // }
            if($sitaan->isNewRecord){
                $count = BarangSitaan::find()->max('ID'); //get last ID
                $sitaan->ID = ($count + 1);    
            }

            if ($sitaan->save()) {
                // record log
                // $log->recordLog($tindakan, $sampel, $oldmodel);

                if ($flag['newRecord'])
                    Yii::$app->session->setFlash('success', 'Berjaya menambah rekod Barang Sitaan.');
                else
                    Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod Barang Sitaan.');
                return $this->redirect(['sitaan', 'nosiri' => $model->NOSIRI]);
            }
        }

        return $this->render('extra/sitaan', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sitaan' => $sitaan,
        ]);
    }

    public function actionSitaanView($nosiri, $ID)
    {
        $model = BarangSitaan::findOne(['ID' => $ID]);

        return $this->render('extra/sitaan-view', [
            'model' => $model,
        ]);
    }

    public function actionSitaanDelete()
    {
        if ($post = Yii::$app->request->post()) {
            $sitaan = BarangSitaan::findOne(['NOSIRI' => $post['nosiri'], 'ID' => $post['idbarang']]);

            if ($sitaan) {
                $sitaan->files = UploadedFile::getInstances($sitaan, 'files');

                if ($files = $sitaan->getAttachments()) {
                    // delete image before delete record -NOR19102022
                    foreach ($files as $file) {
                        $option['initialPreview'][] = $file;
                        $option['initialPreviewConfig'][] = [
                            $sitaan->deleteFile( $nosiri = $sitaan->NOSIRI,
                            $filename = basename($file)),
                    ];
                    // var_dump('test');
                    // exit();
                    }
                }
                
                $sitaan->delete();
                
                // record log
                // $log = new LogActions;
                // $log->recordLog($log::ACTION_DELETE, $barangRampasan);

                Yii::$app->session->setFlash('success', 'Berjaya menghapuskan rekod sitaan barang makanan.' . $sitaan->JENISMAKANAN);
                return $this->redirect(['sitaan', 'nosiri' => $sitaan->NOSIRI]);
            }
        }
    }

    /**
     * Uploading file function
     */
    public function actionFileUpload($idbarang=null)
    {
        $model = $this->findModel(Yii::$app->request->post('NOSIRI'));

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            $flag['newRecord'] = true;
            $models = new BarangSitaan();
            $models->NOSIRI = $model->NOSIRI;

            if ($idbarang) {

                // if id not null, find and assign id to idbarang 
                $models = BarangSitaan::findOne(['NOSIRI' => $model->NOSIRI, 'ID' => $idbarang]); //asign nosiri and idsampelto get current model 
                $flag['newRecord'] = false;
            }else{
                $models->ID = $idbarang;   
            }

            if ($models) {

                $option['initialPreview'] = [];
                $option['initialPreviewConfig'] = [];

                $models->files = UploadedFile::getInstances($models, 'files');

                if ($files = $models->saveAttachment()) {
                    // record log function located at model

                    foreach ($files as $file) {
                        $option['initialPreview'][] = $file;
                        $option['initialPreviewConfig'][] = [
                            'url' => Url::to([
                               '/makanan/sitaan/file-delete',
                                'nosiri' => $models->NOSIRI,
                                'filename' => basename($file),
                            ]),
                        ];
                    }
                }
                return $option;
            }
        }
        return ['error' => Yii::t('app', 'Fail to upload files.')];
    }

    /**
     * Delete file
     */
    public function actionFileDelete($nosiri, $filename)
    {
        $model = $this->findModel($nosiri);
        // $model = BarangSitaan::findOne(['NOSIRI' => $nosiri]);
        $models = new BarangSitaan();
        $models->NOSIRI = $model->NOSIRI;

        if($models){
            $ret = $models->deleteFile($nosiri, $filename);  
        }
        return $ret;
    }

    public function actionGetAhlis($NOSIRI = null) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $output = [];
        $ahliRecords = [];

        $model = $this->findModel($NOSIRI);
            // var_dump($model);
            // exit();
        foreach ($model->pasukanAhlis as $ahli) {
            if ($ahli->JENISPENGGUNA == 2) {
                if (!empty(\common\models\Pengguna::findOne(['ID' => $ahli->IDPENGGUNA, 'STATUS' => 1]))) {
                    $ahliRecords[$ahli->IDPENGGUNA] = $ahli->pengguna0->NAMA;
                }
            }
        }

        $output = $ahliRecords;

        return $output;
    }

}
