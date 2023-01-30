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
use backend\modules\makanan\models\SampelHandswabSearch;
use backend\modules\makanan\models\SampelHandswab;
use backend\components\LogActions;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;

/**
 * HandswabController implements the CRUD actions for Handswab model.
 */
class HandswabController extends Controller
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
                        'allow' => Yii::$app->access->can('HSW-read'),
                        'actions' => ['index', 'view', 'sampel', 'sampel-view', 'get-ahlis'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('HSW-write'),
                        'actions' => ['create', 'update', 'delete', 'sampel', 'sampel-delete','file-upload', 'file-delete'],
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
     * Lists all LawatanMain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LawatanMainSearch();
        $dataProvider = $searchModel->search('HSW', $this->request->queryParams);
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel);
            $actionHandler->execute();
        }

        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'HSW',
            'title' => 'Handswab',
            'breadCrumbs' => ['Mutu Makanan', 'Handswab'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Handswab model.
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
            'idModule' => 'HSW',
            'title' => 'Handswab',
            'breadCrumbs' => ['Mutu Makanan', ['label' => 'Handswab', 'url' => ['index']], $model->NOSIRI, 'Paparan Maklumat'],
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Handswab model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new LawatanMain();

    //     if (Yii::$app->request->post('action')) {
    //         // var_dump('test1');
    //         // exit();
            
    //         $actionHandler = new ActionHandler($model);
    //         $action = $actionHandler->execute();
            
    //         if ($action && $model->load(Yii::$app->request->post())) {
    //             //$model->setNosiri('PKK');  //set No Siri.
    //             $model->setNosiri($model->IDMODULE);  //set No Siri. //zihan
    //             $model->saveMaklumatPemilik(); //save maklumat lesen
    //             $model->saveAhliPasukan(); //save maklumat pasukan
              
    //             if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
    //             if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);
                
    //             $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
    //             $model->IDZON_AM = $idzon_amarr[0];
    //             $model->PRGNLOKASI_AM = $idzon_amarr[1];
                
    //             // $model->PGNDAFTAR = Yii::$app->user->ID;
    //             // $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
    //             // $model->PGNAKHIR = Yii::$app->user->ID;
    //             // $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));
    //             //$model->save(false);

    //             if ($model->save(false)) {

    //                 // record log
    //                 // $log = new LogActions;
    //                 // $log->recordLog($log::ACTION_CREATE, $model);

    //                 Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
    //                 $actionHandler->gotoReturnUrl($model);
    //             }
    //         }
    //     }

    //     // return $this->render('create', [
    //     return $this->render('@backend/modules/lawatanmain/views/create', [
    //         'idModule' => 'HSW',
    //         'titleMain' => 'Mutu Makanan',
    //         'title' => 'Handswab',
    //         'model' => $model,
    //     ]);
    // }

    public function actionCreate()
    {
        $model = new LawatanMain();

       if($model){
    //     var_dump('test1');
    //     exit();
    //    if (Yii::$app->request->post('action')) {
    //     var_dump('test2');
    //     exit();
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

        if ($model->load(Yii::$app->request->post())) {

                $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
                $model->IDZON_AM = $idzon_amarr[0];
                $model->PRGNLOKASI_AM = $idzon_amarr[1];
                $model->IDSUBUNIT = Yii::$app->user->identity->SUBUNIT;

                $model->setNosiri($model->IDMODULE);  //set No Siri.
              
                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);

                $model->PGNDAFTAR = Yii::$app->user->ID;
                $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));

                if ($model->save(false)) {

                    $model->saveMaklumatPemilik();//save maklumat lesen & sewa
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
            'idModule' => 'HSW',
            'titleMain' => 'Mutu Makanan',
            'title' => 'Handswab',
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Kolam model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id Nosiri
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
            
           // if ($action && $model->load(Yii::$app->request->post())) {  
            if ($model->load(Yii::$app->request->post())){

                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);

                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));
                
                if ($model->save(false)) {

                    $model->saveMaklumatPemilik();//save maklumat lesen & sewa
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
            'idModule' => 'HSW',
            'titleMain' => 'Mutu Makanan',
            'title' => 'Handswab',
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Handswab model.
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

     /**
     * Tabmenu Handswab
     * Redirect to anak Handswab page.
     * Save and update data.
     */
    public function actionSampel($nosiri, $idsampel= null)
    {
        $model = $this->findModel($nosiri);

        /* for auditlog */
        $log = new LogActions;
        $tindakan = $log::ACTION_CREATE;
        $oldmodel = null;
        /* */        

        //Filtering search at index page
        $searchModel = new SampelHandswabSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $sampel = new SampelHandswab();
        $sampel->NOSIRI = $model->NOSIRI;

        //If data exists, then the data will display here(Edit form).
        if ($idsampel) {
            $sampel = SampelHandswab::findOne(['ID' => $idsampel]);
            $flag['newRecord'] = false;

            /* for auditlog */
            $tindakan = $log::ACTION_UPDATE;
            $oldmodel = json_encode($sampel->getAttributes());
            /* */            
        }
        
        // start print list function -nor07112022
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-list' => function ($sampel) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    // var_dump($sampel);
                    // exit();

                    $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
                    $mpdf->WriteHTML($this->render('_list', ['dataProvider' => $dataProvider]));
                    // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                    $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
                },
            ]);

            $actionHandler->execute();
        }
        // end print list function

        if ($sampel->load(Yii::$app->request->post()) && $sampel) {

            // if ($sampel->isNewRecord) {//set IDSAMPEL if new record only -NOR130922
            //     $sampel->setIdSampel(); 
            // }

            if($sampel->isNewRecord){
                // $sampel->ID = Yii::$app->db->createCommand("UPDATE TBSAMPEL_HS SET ID = ROWNUM")->execute();  //update sequence of ID -NOR14102022   

                // $count = SampelHandswab::find()->count();
                $count = SampelHandswab::find()->max('ID'); //get last ID
                $sampel->ID = ($count + 1);   
                
            }            
            if ($sampel->save()) {
                // record log
                // $log->recordLog($tindakan, $sampel, $oldmodel);

                if ($flag['newRecord'])
                    Yii::$app->session->setFlash('success', 'Berjaya menambah rekod Sampel Handswab.');
                else
                    Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod Sampel Handswab.');
                return $this->redirect(['sampel', 'nosiri' => $model->NOSIRI]);
            }
        }

        return $this->render('extra/sampel', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sampel' => $sampel,
        ]);
    }


    /**
     * Tabmenu Handswab
     * View data Handswab.
     */
    public function actionSampelView($nosiri, $ID)
    {
        $model = SampelHandswab::findOne(['ID' => $ID]);

        return $this->render('extra/sampel-view', [
            'model' => $model,
        ]);
    }


    /**
     * Tabmenu Handswab
     * Delete all the data Handswab.
     */
    public function actionSampelDelete()
    {
        if ($post = Yii::$app->request->post()) {
            $sampel = SampelHandswab::findOne(['NOSIRI' => $post['nosiri'], 'ID' => $post['idsampel']]);

            if ($sampel) {
                $sampel->files = UploadedFile::getInstances($sampel, 'files');

                if ($files = $sampel->getAttachments()) {
                    // delete image before delete record -NOR19102022
                    foreach ($files as $file) {
                        $option['initialPreview'][] = $file;
                        $option['initialPreviewConfig'][] = [
                            $sampel->deleteFile( $nosiri = $sampel->NOSIRI,
                            $filename = basename($file)),
                    ];
                    // var_dump('test');
                    // exit();
                    }
                }
                $sampel->delete();
                
                // record log
                // $log = new LogActions;
                // $log->recordLog($log::ACTION_DELETE, $barangRampasan);

                Yii::$app->session->setFlash('success', 'Berjaya menghapuskan rekod sampel handswab.' . $sampel->IDSAMPEL);
                return $this->redirect(['sampel', 'nosiri' => $sampel->NOSIRI]);
            }
        }
    }


    /**
     * Tabmenu/anak Handswab 
     * Uploading file function
     */
    public function actionFileUpload($idsampel = null)
    {
        $model = $this->findModel(Yii::$app->request->post('NOSIRI'));

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            $flag['newRecord'] = true;
            $models = new SampelHandswab();
            $models->NOSIRI = $model->NOSIRI;

            if ($idsampel) {
                // var_dump('test3');
                // exit();

                // if id not null, find and assign id to idsampel 
                $models = SampelHandswab::findOne(['NOSIRI' => $model->NOSIRI, 'ID' => $idsampel]); //asign nosiri and idsampelto get current model 
                $flag['newRecord'] = false;
            }else{
                $models->ID = $idsampel;   
            }

            if ($models) {

                $option['initialPreview'] = [];
                $option['initialPreviewConfig'] = [];

                $models->files = UploadedFile::getInstances($models, 'files');

                // var_dump($models->files);
                // exit();


                if ($files = $models->saveAttachment()) {
                    // record log function located at model

                    foreach ($files as $file) {
                        $option['initialPreview'][] = $file;
                        $option['initialPreviewConfig'][] = [
                            'url' => Url::to([
                               '/makanan/handswab/file-delete',
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
     * Tabmenu Handswab
     * Form edit/new record.
     * Images akan dipadam apabila user click button 'trash'.
     */
    public function actionFileDelete($nosiri, $filename)
    {
        $model = $this->findModel($nosiri);
        // $model = SampelHandswab::findOne(['NOSIRI' => $nosiri]);
        $models = new SampelHandswab();
        $models->NOSIRI = $model->NOSIRI;

        if($models){
            $ret = $models->deleteFile($nosiri, $filename);  
        }
        return $ret;
    }

}
