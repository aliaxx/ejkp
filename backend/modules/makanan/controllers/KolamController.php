<?php

namespace backend\modules\makanan\controllers;

use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanMainSearch;
use backend\modules\penyelenggaraan\models\BacaanKolam;
use backend\modules\makanan\models\Transkolam;
use backend\modules\makanan\models\TranskolamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use Yii;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use yii\web\Response;   
use yii\web\UploadedFile;
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
 * KolamController implements the CRUD actions for Kolam model.
 */
class KolamController extends Controller
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
                        'allow' => Yii::$app->access->can('PKK-read'),
                        'actions' => ['index', 'view', 'print', 'get-ahlis', 'get-item-form','get-param-details','gambar','file-download'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('PKK-write'),
                        'actions' => ['create', 'update', 'delete', 'kolam', 'file-upload','file-delete'],
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
     * Lists all Kolam models.
     * @return mixed
     */
    public function actionIndex()
    {
   
        $searchModel = new LawatanMainSearch();
        $dataProvider = $searchModel->search('PKK', $this->request->queryParams);
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                
                'export-pdf' => function ($model) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('_print-grid', ['dataProvider' => $dataProvider]));
                    $mpdf->Output(Yii::$app->controller->id . ' PemeriksaanKolam.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                },
            ]);
            $actionHandler->execute();
        }

        //return $this->render('index', [
        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'PKK',
            'title' => 'Pemeriksaan Kolam',
            'breadCrumbs' => ['Mutu Makanan', 'Pemeriksaan Kolam'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kolam model.
     * @param string $id Nosiri
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        // var_dump($module);
        // exit();

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $actionHandler->execute();

            // Refresh model information
            $model = $this->findModel($id);
        }

        return $this->render('@backend/modules/lawatanmain/views/view', [
            'idModule' => 'PKK',
            'title' => 'Pemeriksaan Kolam',
            'breadCrumbs' => ['Mutu Makanan', ['label'=>'Pemeriksaan Kolam', 'url' => ['index']], $model->NOSIRI, 'Paparan Maklumat'],
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Kolam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LawatanMain();

        // var_dump(Yii::$app->request->post('action'));
        // exit;

        if($model){
        // if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();
            
            if ($model->load(Yii::$app->request->post())) {
            // if ($action && $model->load(Yii::$app->request->post())) {

                $model->setNosiri($model->IDMODULE);  //set No Siri. //zihan
              
                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);

                $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
                $model->IDZON_AM = $idzon_amarr[0];
                $model->PRGNLOKASI_AM = $idzon_amarr[1];
                
                $model->PGNDAFTAR = Yii::$app->user->ID;
                $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));

                if ($model->save(false)) {

                    $model->saveMaklumatPemilik(); //save maklumat lesen/sewa
                    $model->saveAhliPasukan(); //save maklumat pasukan
                    // var_dump($model->saveAhliPasukan());
                    // exit;

                    // record log
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_CREATE, $model);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }

        // return $this->render('create', [
        return $this->render('@backend/modules/lawatanmain/views/create', [
            'idModule' => 'PKK',
            'titleMain' => 'Mutu Makanan',
            'title' => 'Pemeriksaan Kolam',
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
            
            if ($model->load(Yii::$app->request->post())){
            // if ($action && $model->load(Yii::$app->request->post())){

                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);
                
                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));
                
                if ($model->save(false)) {

                    $model->saveMaklumatPemilik(); //save maklumat lesen
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
            'idModule' => 'PKK',
            'titleMain' => 'Mutu Makanan',
            'title' => 'Pemeriksaan Kolam',
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Kolam model.
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
     * Finds the Kolam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $NOSIRI Nosiri
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
     * Dynamic row at _tab/kolam_itemform.
     */
    public function actionGetItemForm($counter, $initialize = false)
    {

        if (Yii::$app->request->isAjax) {
            if (!$initialize) {
                $modelkolam = new Transkolam();
                return $this->renderAjax('extra/kolam_itemform', [
                    'modelkolam' => $modelkolam,
                    'counter' => $counter,
                ]);
            } else {
                $modelkolam = Transkolam::findOne(['NOSIRI' => $initialize]);
                $modelkolam = $modelkolam->airkolam;

                //display data on textbox
                if ($modelkolam) {
                    $htmlOutput = [];
                    foreach ($modelkolam as $key => $item) { 
                        $data = [];
                        $modelkolam = new Transkolam();
                        $data['IDPARAM'] = $item->IDPARAM;
                        $data['NILAIPIAWAI'] = $item->bacaankolam->NILAIPIAWAI;
                        $data['UNIT'] = $item->bacaankolam->UNIT;
                        $data['NILAI1'] = $item->NILAI1;
                        $data['NILAI2'] = $item->NILAI2;
                        $data['NILAI3'] = $item->NILAI3;
                        $data['NILAI4'] = $item->NILAI4;

                        $htmlOutput[] = $this->renderAjax('extra/kolam_itemform', [
                            'modelkolam' => $modelkolam,
                            'counter' => ($key + 1),
                            'tmpData' => $data,
                        ]);
                    }
                    if (count($htmlOutput) > 0) return implode(null, $htmlOutput);
                }
            }
        }
        return null;
    }


    /**
     * Tabmenu Pemeriksaan Kolam
     * Redirect to anak Pemeriksaan Kolam page.
     * Save and update data.
     */
    public function actionKolam($nosiri, $idparam= null)
    {
        $model = $this->findModel($nosiri);

        /* for auditlog */
        $log = new LogActions;
        $tindakan = $log::ACTION_CREATE;
        $oldmodel = null;
        /* */        

        //Filtering search at index page
        $searchModel = new TranskolamSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $modelkolam = new Transkolam();
        $modelkolam->NOSIRI = $model->NOSIRI;

        //If data exists, then the data will display here(Edit form).
        if ($idparam) {
            $modelkolam = Transkolam::findOne(['ID' => $idparam]);
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
                'export-printkolam' => function ($modelkolam) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    // var_dump($sampel);
                    // exit();

                    $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
                    $mpdf->WriteHTML($this->render('_printkolam', ['dataProvider' => $dataProvider]));
                    // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                    $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
                },
            ]);

            $actionHandler->execute();
        }
        // end print list function        
        
        //save data 
        if ($modelkolam->load(Yii::$app->request->post())) {
            $ccount = count($modelkolam->IDPARAM);

            $ID[] = null;

            Transkolam::deleteAll(['NOSIRI' => $modelkolam->NOSIRI]);

            $modelkolam->ID = Yii::$app->db->createCommand("UPDATE TBTRANS_KOLAM SET ID = ROWNUM")->execute();     

            for ($i = 0; $i < $ccount; $i++) {

                $count = Transkolam::find()->count();
                $modelkolam->ID = ($count + 1);

                $models = new Transkolam();
                $ID[$i] = $modelkolam->ID;

                $models->ID = $ID[$i];    
                $models->NOSIRI = $modelkolam->NOSIRI;
                $models->IDPARAM = $modelkolam->IDPARAM[$i];
                $models->NILAI1 = $modelkolam->NILAI1[$i];
                $models->NILAI2 = $modelkolam->NILAI2[$i];
                $models->NILAI3 = $modelkolam->NILAI3[$i];
                $models->NILAI4 = $modelkolam->NILAI4[$i];
                $models->PGNDAFTAR = Yii::$app->user->ID;
                $models->TRKHDAFTAR = strtotime(date("Y-m-d H:i:s"));
                $models->PGNAKHIR = Yii::$app->user->ID;
                $models->TRKHAKHIR = strtotime(date("Y-m-d H:i:s"));
                $models->save(false);
            }

            //tak perlu {} kalau ada 1 line process
            if ($flag['newRecord'])
                Yii::$app->session->setFlash('success', 'Berjaya menambah rekod parameter air kolam.');
            else
                Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod parameter air kolam.');
            return $this->redirect(['kolam', 'nosiri' => $model->NOSIRI]);
       
        }

        return $this->render('extra/kolam', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelkolam' => $modelkolam,
        ]);
    }


    /**
     * Tabmenu Pemeriksaan Kolam.
     * Parameter Air Kolam page.
     * Get data BacaanKolam to autofill the textbox.
    */
    public function actionGetParamDetails($idparam = null) //$namaparam is object that declare at js script
    {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (!empty($idparam)) {
                $query = BacaanKolam::find()->where(['ID' => $idparam]);
                $results = $query->one();

                if (!empty($results)) {
                    $output['results'] = [
                        'ID' => $results->ID, //get namaparam
                        'NILAIPIAWAI' => $results->NILAIPIAWAI, 
                        'UNIT' => $results->UNIT,
                    ];
                }
                return $output;
            }    
    }


    /**
     * Tabmenu Pemeriksaan Kolam.
     * Redirect to page Gambar at LawatanMain.
    */
    public function actionGambar($nosiri)
    {
        $model = $this->findModel($nosiri);

        return $this->render('@backend/modules/lawatanmain/views/gambar', [
            'idModule' => 'PKK',
            'title' => 'Pemeriksaan Kolam',
            'titleMain' => 'Mutu Makanan',
            'model' => $model,
        ]);
    }


    /**
     * Tabmenu Pemeriksaan Kolam
     * Print Pemeriksaan Kolam document 
     * and display it inline at browser
     */
    public function actionPrint($id)
    {
        $model = $this->findModel($id);
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($this->render('_print', ['model' => $model]));
        $mpdf->Output($model->NOSIRI . '.pdf', Destination::INLINE);
    }


}
