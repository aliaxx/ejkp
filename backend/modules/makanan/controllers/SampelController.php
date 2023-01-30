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
use backend\modules\makanan\models\SampelMakanan;
use backend\modules\makanan\models\SampelMakananSearch;

use backend\components\LogActions;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;


/**
 * MakananController implements the CRUD actions for Makanan model.
 */
class SampelController extends Controller
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
                        'allow' => Yii::$app->access->can('SMM-read'),
                        'actions' => ['index', 'view', 'sampel', 'sampel-view', 'get-ahlis', 'print'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('SMM-write'),
                        'actions' => ['create', 'update', 'delete', 'sampel', 'sampel-delete','file-upload', 'file-delete', 'print'],
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
     * Lists all Makanan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LawatanMainSearch();
        $dataProvider = $searchModel->search('SMM', $this->request->queryParams);

        ActionHandler::setReturnUrl();
        //to render daftar button in index page -> go to the form
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel);
            $actionHandler->execute();
        }

        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'SMM',
            'title' => 'Sampel Makanan',
            'breadCrumbs' => ['Mutu Makanan', 'Sampel Makanan'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Makanan model.
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
            'idModule' => 'SMM',
            'title' => 'Sampel Makanan',
            'breadCrumbs' => ['Mutu Makanan', ['label'=>'Sampel Makanan', 'url' => ['index']], $model->NOSIRI, 'Paparan Maklumat'],
            'model' => $model,
        ]);
    }

    /**
     * Creates Sampel new record based on LawatanMain model.
     * If creation is successful, the browser will be redirected to the 'index' page.
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
                // if ($action && $model->load(Yii::$app->request->post()) && $model->validate()) {      
                
                $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
                $model->IDZON_AM = $idzon_amarr[0];
                $model->PRGNLOKASI_AM = $idzon_amarr[1];
                $model->IDSUBUNIT = Yii::$app->user->identity->SUBUNIT;
    
                //$model->setNosiri('SMM'); //set No Siri.    
                $model->setNosiri($model->IDMODULE);  //set No Siri. //zihan
                
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

                    // Yii::$app->session->setFlash('success', 'Rekod ' . $model->NOSIRI . ' telah berjaya disimpan.');
                    // return $this->redirect(['/makanan/sampel/view', 'id' => $model->NOSIRI]);
                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                    // $actionHandler->gotoReturnUrl($model);
                }
                // print_r($model->errors);
                // exit();
            }
        }

        // return $this->render('create', [
        return $this->render('@backend/modules/lawatanmain/views/create', [
            'idModule' => 'SMM',
            'titleMain' => 'Mutu Makanan',
            'title' => 'Sampel Makanan',
            'model' => $model,
        ]);
    }

   

    /**
     * Updates an existing Makanan model.
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
            
            if ($model->load(Yii::$app->request->post())){
            // if ($action && $model->load(Yii::$app->request->post())){

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

        // return $this->render('update', [
        return $this->render('@backend/modules/lawatanmain/views/update', [
            'idModule' => 'SMM',
            'titleMain' => 'Mutu Makanan',
            'title' => 'Sample Makanan',
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Makanan model.
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
     * Finds the Makanan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $NOSIRI Nosiri
     * @return Makanan the loaded model
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
     * Tabmenu Sampel Makanan
     * Redirect to anak Sampel Makanan page.
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
        $searchModel = new SampelMakananSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $sampel = new SampelMakanan();
        $sampel->NOSIRI = $model->NOSIRI;

        //If data exists, then the data will display here(Edit form).
        if ($idsampel) {
            $sampel = SampelMakanan::findOne(['ID' => $idsampel]);
            $flag['newRecord'] = false;

            /* for auditlog */
            $tindakan = $log::ACTION_UPDATE;
            $oldmodel = json_encode($sampel->getAttributes());
            /* */            
        }

        // start print list function - nur190521
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-permintaan' => function ($sampel) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    // var_dump($sampel);
                    // exit();

                    $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
                    $mpdf->WriteHTML($this->render('_permintaan', ['dataProvider' => $dataProvider]));
                    // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                    $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
                },
                'export-notis' => function ($sampel) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
                    $mpdf->WriteHTML($this->render('_notis', ['dataProvider' => $dataProvider]));
                    // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                    $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
                },
                'export-resit' => function ($sampel) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
                    $mpdf->WriteHTML($this->render('_resit', ['dataProvider' => $dataProvider]));
                    // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                    $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
                },
            ]);

            $actionHandler->execute();
        }
        // end print list function            

        if ($sampel->load(Yii::$app->request->post()) && $sampel) {
            // var_dump($sampel);
            // exit();

            if($sampel->isNewRecord){
                // $sampel->ID = Yii::$app->db->createCommand("UPDATE TBSAMPEL_SM SET ID = ROWNUM")->execute();  //update sequence of ID -NOR14102022   

                // $count = SampelMakanan::find()->count();
                $count = SampelMakanan::find()->max('ID'); //get last ID
                $sampel->ID = ($count + 1);            
            }

            // if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
            // if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);
            if ($sampel->TRKHSAMPEL) $sampel->TRKHSAMPEL = DateTimeHelper::convert($sampel->TRKHSAMPEL, true);

            if ($sampel->save()) {
                // record log
                // $log->recordLog($tindakan, $sampel, $oldmodel);

                // var_dump($path);
                // exit();

                if ($flag['newRecord'])
                    Yii::$app->session->setFlash('success', 'Berjaya menambah rekod Barang sampel '. $sampel->NOSAMPEL);
                else
                    Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod Barang sampel '. $sampel->NOSAMPEL);
                return $this->redirect(['sampel', 'nosiri' => $model->NOSIRI]); //sampel adalah action -NOR22092022
            }
            // print_r($sampel->errors);
            // exit();
        }

        return $this->render('extra/sampel-makanan', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sampel' => $sampel,
        ]);
    }


    /**
     * Tabmenu Sampel Makanan
     * View data Sampel Makanan.
     */
    public function actionSampelView($nosiri, $ID)
    {
        $model = SampelMakanan::findOne(['ID' => $ID]);

        return $this->render('extra/sampelmakanan-view', [
            'model' => $model,
        ]);
    }


    /**
     * Tabmenu Sampel Makanan
     * Delete all the data Sampel Makanan.
     */
    public function actionSampelDelete()
    {
        if ($post = Yii::$app->request->post()) {
            $sampel = SampelMakanan::findOne(['NOSIRI' => $post['nosiri'], 'ID' => $post['idsampel']]);

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

                Yii::$app->session->setFlash('success', 'Berjaya menghapuskan rekod sampel makanan ' . $sampel->NOSAMPEL);
                return $this->redirect(['sampel', 'nosiri' => $sampel->NOSIRI]);
            }
        }
    }


    /**
     * Tabmenu/anak Sampel Makanan 
     * Uploading file function
     */
    public function actionFileUpload($idsampel=null) //amend by NOR18102022
    {
        $model = $this->findModel(Yii::$app->request->post('NOSIRI'));

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {
            // $model = $this->findModel(Yii::$app->request->post('NOSIRI'));
            // $models = SampelMakanan::findOne(['NOSIRI' => $model->NOSIRI, 'ID' => $idsampel]);
            $flag['newRecord'] = true;
            $models = new SampelMakanan();
            $models->NOSIRI = $model->NOSIRI;

            if ($idsampel) {
                // var_dump('test3');
                // exit();

                // if id not null, find and assign id to idsampel 
                $models = SampelMakanan::findOne(['NOSIRI' => $model->NOSIRI, 'ID' => $idsampel]); //asign nosiri and idsampelto get current model 
                $flag['newRecord'] = false;
            }else{
                // var_dump('test4');
                // exit();
                // $flag['newRecord'] = true;
                // $models1 = new SampelMakanan();
                // $models1->NOSIRI = $model->NOSIRI;
                $models->ID = $idsampel;   
                // $models1->save(false);

                // var_dump($models1->ID);
                // exit();
                // var_dump($idsampel);
                // exit();

            }

            // var_dump($models->NOSIRI);
            // exit();

            // if ($models == null || $models != null) {
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
                               '/makanan/sampel/file-delete',
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
     * Tabmenu Sampel Makanan
     * Form edit/new record.
     * Images akan dipadam apabila user click button 'trash'.
     */
    public function actionFileDelete($nosiri, $filename)
    {
        $model = $this->findModel($nosiri);
        // $model = SampelMakanan::findOne(['NOSIRI' => $nosiri]); //RETURN NULL AND ERROR TO $ret -NOR25102022
        $models = new SampelMakanan();
        $models->NOSIRI = $model->NOSIRI;

        if($models){
            $ret = $models->deleteFile($nosiri, $filename);  
        }
        return $ret;
    }

 

    // public function actionPrint($nosiri, $ID)
    // {
    //     var_dump('test');
    //     exit();

    //     $model = SampelMakanan::findOne(['ID' => $ID]);

    //     // start print list function - nur190521
    //     ActionHandler::setReturnUrl();
    //     if (Yii::$app->request->post('action')) {
    //         $actionHandler = new ActionHandler($searchModel, [
    //             'export-permintaan' => function ($model) use ($dataProvider) {
    //                 $dataProvider->setPagination(false);

    //                 $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
    //                 $mpdf->WriteHTML($this->render('_permintaan', ['dataProvider' => $dataProvider]));
    //                 // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    //                 $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
    //             },
    //             'export-notis' => function ($model) use ($dataProvider) {
    //                 $dataProvider->setPagination(false);

    //                 $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
    //                 $mpdf->WriteHTML($this->render('_notis', ['dataProvider' => $dataProvider]));
    //                 // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    //                 $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
    //             },
    //             'export-resit' => function ($model) use ($dataProvider) {
    //                 $dataProvider->setPagination(false);

    //                 $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
    //                 $mpdf->WriteHTML($this->render('_resit', ['dataProvider' => $dataProvider]));
    //                 // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    //                 $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
    //             },
    //         ]);

    //         $actionHandler->execute();
    //     }
    //     // end print list function    

    //     return $this->render('/_print', [
    //         'model' => $model,
    //     ]);

    // }

}
