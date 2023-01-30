<?php

namespace backend\modules\vektor\controllers;

use Yii;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\LogActions;
use yii\web\NotFoundHttpException;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanMainSearch;
use backend\modules\vektor\models\SasaranLvc;
use backend\modules\vektor\models\SasaranLvcSearch;
use backend\modules\vektor\models\BekasLvc;
use backend\modules\vektor\models\BekasLvcSearch;
use backend\modules\vektor\models\Lvcaktiviti;
use backend\modules\vektor\models\LvcaktivitiSeacrh;

/**
 * SrtController implements the CRUD actions for Srt model.
 */
class LvcController extends Controller
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
                        'allow' => Yii::$app->access->can('LVC-read'),
                        'actions' => ['index', 'view', 'sasaran', 'get-sasaran-form', 'bekas-view', 'liputan', 'get-ahlis', 'gambar', 
                        'print'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('LVC-write'),
                        'actions' => ['create', 'update', 'delete', 'sasaran', 'get-sasaran-form', 'liputan', 'bekas', 
                        'bekas-delete','file-upload', 'file-delete', 'gambar', 'print', 'larvisid', 'larvisid-view', 'larvisid-delete'],
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
     * Lists all Srt models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LawatanMainSearch();
        $dataProvider = $searchModel->search('LVC',$this->request->queryParams);

        ActionHandler::setReturnUrl();
        //to render daftar button in index page -> go to the form ->NOR 260922
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel);
            $actionHandler->execute();
        }

        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'LVC',
            'title' => 'Aktiviti Larvaciding',
            'breadCrumbs' => ['Pencegahan Vektor', 'Aktiviti Larvaciding'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Handswab model.
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
            'idModule' => 'LVC',
            'title' => 'Aktiviti Larvaciding',
            'breadCrumbs' => ['Pencegahan Vektor', ['label'=>'Aktiviti Larvaciding', 'url' => ['index']], $model->NOSIRI, 'Paparan Maklumat'],
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Handswab model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LawatanMain();

        // var_dump(Yii::$app->request->post('action'));
        // exit;

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();
            
            if ($action && $model->load(Yii::$app->request->post())) {

                $model->setNosiri($model->IDMODULE);  //set No Siri. //zihan
                $model->saveAhliPasukan(); //save maklumat pasukan
              
                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);
                if ($model->V_TRKHKEYIN) $model->V_TRKHKEYIN = DateTimeHelper::convert($model->V_TRKHKEYIN, true);
                if ($model->V_TRKHNOTIFIKASI) $model->V_TRKHNOTIFIKASI = DateTimeHelper::convert($model->V_TRKHNOTIFIKASI, true);
                
                $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
                $model->IDZON_AM = $idzon_amarr[0];
                $model->PRGNLOKASI_AM = $idzon_amarr[1];
                
                $model->PGNDAFTAR = Yii::$app->user->ID;
                $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));

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
            'idModule' => 'LVC',
            'title' => 'Aktiviti Larvaciding',
            'titleMain' => 'Pencegahan Vektor',
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Handswab model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $NOSIRI Nosiri
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
            
            if ($action && $model->load(Yii::$app->request->post())){

                $model->saveAhliPasukan(); //save maklumat pasukan

                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);
                if ($model->V_TRKHKEYIN) $model->V_TRKHKEYIN = DateTimeHelper::convert($model->V_TRKHKEYIN, true);
                if ($model->V_TRKHNOTIFIKASI) $model->V_TRKHNOTIFIKASI = DateTimeHelper::convert($model->V_TRKHNOTIFIKASI, true);
                
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
            'idModule' => 'LVC',
            'title' => 'Aktiviti Larvaciding',
            'titleMain' => 'Pencegahan Vektor',
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Handswab model.
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
     * Finds the Srt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $NOSIRI Nosiri
     * @return Srt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LawatanMain::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Manipulating Handswab record
     * @param $nosiri
     * @param $idbarang
     * @return mixed
     */
    public function actionSasaran($nosiri, $idsasaran= null)
    {
        $model = $this->findModel($nosiri);

        // var_dump($model);
        // exit();
        /* for auditlog */
        // $log = new LogActions;
        // $tindakan = $log::ACTION_CREATE;
        // $oldmodel = null;
        /* */        

        $searchModel = new SasaranLvcSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $sasaran = new SasaranLvc();
        $sasaran->NOSIRI = $model->NOSIRI;

        if ($idsasaran) {
            $sasaran = SasaranLvc::findOne(['ID' => $idsasaran]);
            $flag['newRecord'] = false;

            /* for auditlog */
            // $tindakan = $log::ACTION_UPDATE;
            // $oldmodel = json_encode($sampel->getAttributes());
            /* */            
        }

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {
        
                if ($sasaran->load(Yii::$app->request->post())) {

                    // var_dump($sasaran);
                    // exit();
    
                    if($model->save(false)){
                        $ccount = count($sasaran->ID_JENISPREMIS);

                        $ID[] = null;
                               
                        SasaranLvc::deleteAll(['NOSIRI' => $sasaran->NOSIRI]);

                        $sasaran->ID = Yii::$app->db->createCommand("UPDATE TBSASARAN_PTP SET ID = ROWNUM")->execute();     

                            // var_dump($sasaran->ID);
                            // exit();                   
                        
                        for ($i=0; $i < $ccount; $i++) {

                            $count = SasaranLvc::find()->count();
                            $sasaran->ID = ($count + 1);
            
                            $models = new SasaranLvc();

                            $ID[$i] = $sasaran->ID;

                            $models->ID = $ID[$i];    
                            // var_dump($models->ID);
                            // exit();                   
                            $models->NOSIRI = $sasaran->NOSIRI;
                            $models->ID_JENISPREMIS = $sasaran->ID_JENISPREMIS[$i];
                            // var_dump($models->ID_JENISPREMIS);
                            // exit();       
                            $models->SASARAN = $sasaran->SASARAN[$i];
                            $models->PENCAPAIAN = $sasaran->PENCAPAIAN[$i];
                            $models->JUMPOSITIF = $sasaran->JUMPOSITIF[$i];
                            $models->save(false);
                        }

                        // var_dump($models);
                        // exit();
            
                        if ($flag['newRecord'])
                            Yii::$app->session->setFlash('success', 'Berjaya menambah rekod sasaran semburan.');
                        else
                            Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod sasaran semburan.');
                        return $this->redirect(['sasaran', 'nosiri' => $model->NOSIRI]);
            
                    }
                }
            }
        }

        // print_r($model->errors);
        // exit;


        return $this->render('extra/sasaran', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sasaran' => $sasaran,
        ]);
    }

    public function actionGetSasaranForm($counter, $initialize = false)
    {
        if (Yii::$app->request->isAjax) {

            if (!$initialize) {
                $sasaran = new SasaranLvc();           
                return $this->renderAjax('extra/_sasaran-form', [
                    'sasaran' => $sasaran,
                    'counter' => $counter,
                ]);
            } else {
                $sasaran = SasaranLvc::findOne(['NOSIRI' => $initialize]);
                $sasaran = $sasaran->premis;
                // var_dump($sasaran);
                // exit();

                if ($sasaran) {
                    $htmlOutput = [];
                    foreach ($sasaran as $key => $item) {
                        $data = [];
                        $sasaran = new SasaranLvc();                        
                        $data['ID_JENISPREMIS'] = $item->ID_JENISPREMIS;
                        $data['SASARAN'] = $item->SASARAN;
                        $data['PENCAPAIAN'] = $item->PENCAPAIAN;
                        $data['JUMPOSITIF'] = $item->JUMPOSITIF;

                        $htmlOutput[] = $this->renderAjax('extra/_sasaran-form', [
                        'sasaran' => $sasaran,
                        'counter' => ($key + 1),
                        'tmpData' => $data,
                        ]);                      
                    }               
                    
                    if (count($htmlOutput) > 0) return implode(null, $htmlOutput);                    
                }
            }
            // var_dump($htmlOutput);
            // exit();
        }
        return null;
    }

            /**
     * Manipulating Handswab record
     * @param $nosiri
     * @param $idbarang
     * @return mixed
     */
    public function actionLiputan($nosiri)
    {
        $model = $this->findModel($nosiri);

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            // old data for logging update action
            $oldmodel = json_encode($model->getAttributes());

            if ($action && $model->load(Yii::$app->request->post())) {

                if ($model->save(false)) {
                    
                    // record log
                    // $log = new LogActions;
                    // $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    // $actionHandler->gotoReturnUrl($model); //return to index page -NOR07102022
                }
            }
        }

        return $this->render('extra/liputan', [
            'model' => $model,
        ]);
    }


    /**
     * Uploading file function
     */
    // public function actionFileUpload()
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;

    //     if (Yii::$app->request->isAjax) {
    //         $model = $this->findModel(Yii::$app->request->post('NOSIRI'));
    //         // var_dump($model);
    //         // exit();
    
    //         if ($model) {
    //             $option['initialPreview'] = [];
    //             $option['initialPreviewConfig'] = [];

    //             $model->files = UploadedFile::getInstances($model, 'files');

    //             if ($files = $model->saveAttachment()) {
    //                 foreach ($files as $file) {
    //                     $option['initialPreview'][] = $file;
    //                     $option['initialPreviewConfig'][] = [
    //                         'url' => Url::to([
    //                             '/makanan/handswab/file-delete',
    //                             'NOSIRI' => $model->NOSIRI,
    //                             'filename' => basename($file),
    //                         ]),
    //                     ];
    //                 }
    //             }

    //             // var_dump($file);
    //             // exit();

    //             return $option;
    //         }
    //     }

    //     return ['error' => Yii::t('app', 'Fail to upload files.')];
    // }

    /**
     * Delete file
     */
    // public function actionFileDelete($NOSIRI, $filename)
    // {
    //     if (Yii::$app->request->isAjax) {
    //         $model = SampelHandswab::findOne(['NOSIRI' => $NOSIRI, 'IDSAMPEL' => $filename]);
    //         if ($model) {
    //             $model->status = 2;
    //             $model->save(false);

    //             // record log
    //             // $log = new LogActions;
    //             // $log->recordLog($log::ACTION_UPLOAD_DELETE, $model);

    //             return true;
    //         }
    //     }
    //     return false;
    // }

    /**
     * Manipulating bekas record
     * @param $nosiri
     * @param $idbekas
     * @return mixed
     */
    public function actionBekas($nosiri, $idbekas= null)
    {
        $model = $this->findModel($nosiri);
        /* for auditlog */
        // $log = new LogActions;
        // $tindakan = $log::ACTION_CREATE;
        // $oldmodel = null;
        /* */        

        $searchModel = new BekasLvcSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $bekas = new BekasLvc();
        $bekas->NOSIRI = $model->NOSIRI;

        if ($idbekas) {
            $bekas = BekasLvc::findOne(['ID' => $idbekas]);
            $flag['newRecord'] = false;

            /* for auditlog */
            // $tindakan = $log::ACTION_UPDATE;
            // $oldmodel = json_encode($sampel->getAttributes());
            /* */            
        }
       
        if (Yii::$app->request->post('action')) {
            // var_dump($bekas);
            // exit();
    
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {
        
                if ($bekas->load(Yii::$app->request->post()) && $bekas) {

                    if($bekas->isNewRecord){
                        $bekas->ID = Yii::$app->db->createCommand("UPDATE TBBEKAS_LVC SET ID = ROWNUM")->execute();     

                        $count = BekasLvc::find()->count();
                        $bekas->ID = ($count + 1);    
                    }

                    if($model->save(false) && $bekas->save()){ 
                        // if($bekas->save()){      
                        

                        if ($flag['newRecord'])
                            Yii::$app->session->setFlash('success', 'Berjaya menambah rekod Bekas Diperiksa.');
                        else
                            Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod Bekas Diperiksa.');
                        return $this->redirect(['bekas', 'nosiri' => $model->NOSIRI]);

                        // var_dump($bekas);
                        // exit();    
                        // }
            
                    }
                }
                // print_r($bekas->errors);
                // exit();
            }
        }


        return $this->render('extra/bekas', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'bekas' => $bekas,
        ]);
    }

    public function actionBekasView($nosiri, $ID)
    {
        $model = BekasLvc::findOne(['ID' => $ID]);

        return $this->render('extra/bekas-view', [
            'model' => $model,
        ]);
    }

    public function actionBekasDelete()
    {
        if ($post = Yii::$app->request->post()) {
            $bekas = BekasLvc::findOne(['NOSIRI' => $post['nosiri'], 'ID' => $post['idbekas']]);
            if ($bekas) {
                $bekas->delete();
                
                // record log
                // $log = new LogActions;
                // $log->recordLog($log::ACTION_DELETE, $barangRampasan);

                Yii::$app->session->setFlash('success', 'Berjaya menghapuskan rekod Bekas Diperiksa ' . $bekas->ID);
                return $this->redirect(['bekas', 'nosiri' => $bekas->NOSIRI]);
            }
        }
    }

    /**
     * Manipulating bekas record
     * @param $nosiri
     * @param $idbekas
     * @return mixed
     */
    public function actionLarvisid($nosiri, $idlarvisid= null)
    {
        $model = $this->findModel($nosiri);
        /* for auditlog */
        $log = new LogActions;
        $tindakan = $log::ACTION_CREATE;
        $oldmodel = null;
        /* */        

        $searchModel = new LvcaktivitiSeacrh();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $larvisid = new Lvcaktiviti();
        $larvisid->NOSIRI = $model->NOSIRI;

        if ($idlarvisid) {
            $larvisid = Lvcaktiviti::findOne(['ID' => $idlarvisid]);
            $flag['newRecord'] = false;

            $larvisid->ID = $idlarvisid;

            /* for auditlog */
            $tindakan = $log::ACTION_UPDATE;
            $oldmodel = json_encode($larvisid->getAttributes());
            /* */            
        }
       
        if ($larvisid->load(Yii::$app->request->post()) && $larvisid) {
                        
            if($larvisid->isNewRecord){
                $larvisid->ID = Yii::$app->db->createCommand("UPDATE TBLVC_AKTIVITI SET ID = ROWNUM")->execute();     

                $count = Lvcaktiviti::find()->count();
                // $count = RacunSrt::find()->max('ID'); //get last ID
                $larvisid->ID = ($count + 1);    
            }

            if ($larvisid->save()) {
                // record log
                 $log->recordLog($tindakan, $larvisid, $oldmodel);

                if ($flag['newRecord'])
                    Yii::$app->session->setFlash('success', 'Berjaya menambah rekod Aktiviti Larvisid.');
                else
                    Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod Aktiviti Larvisid.');
                return $this->redirect(['larvisid', 'nosiri' => $model->NOSIRI]);
            }
            // print_r($larvisid->errors);
            // exit();
        }

        return $this->render('extra/larvisid', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'larvisid' => $larvisid,
        ]);    
    }

    public function actionLarvisidView($nosiri, $ID)
    {
        $model = Lvcaktiviti::findOne(['ID' => $ID]);

        return $this->render('extra/larvisid-view', [
            'model' => $model,
        ]);
    }

    public function actionLarvisidDelete()
    {
        if ($post = Yii::$app->request->post()) {
            $larvisid = Lvcaktiviti::findOne(['NOSIRI' => $post['nosiri'], 'ID' => $post['idlarvisid']]);
            if ($larvisid) {
                $larvisid->delete();
                
                // record log
                // $log = new LogActions;
                // $log->recordLog($log::ACTION_DELETE, $barangRampasan);

                Yii::$app->session->setFlash('success', 'Berjaya menghapuskan rekod Aktiviti Larvisid ' . $larvisid->ID);
                return $this->redirect(['larvisid', 'nosiri' => $larvisid->NOSIRI]);
            }
        }
    }

    /**
     * Redirect to lawatanmain/views/gambar page
     */
    public function actionGambar($nosiri)
    {
        $model = $this->findModel($nosiri);

        return $this->render('@backend/modules/lawatanmain/views/gambar', [
            'idModule' => 'LVC',
            'title' => 'Aktiviti Larvaciding',
            'titleMain' => 'Pencegahan Vektor',
            'model' => $model,
        ]);
    }

    public function actionPrint($id)
    {
        $model = $this->findModel($id);

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($this->render('_print', ['model' => $model]));
        $mpdf->Output($model->NOSIRI . '.pdf', Destination::INLINE);
    }

}
