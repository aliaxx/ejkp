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
use backend\modules\vektor\models\SasaranPtp;
use backend\modules\vektor\models\SasaranPtpSearch;
use backend\modules\vektor\models\BekasPtp;
use backend\modules\vektor\models\BekasPtpSearch;
use backend\modules\vektor\models\PenguatkuasaanPtp;
use backend\modules\vektor\models\PenguatkuasaanPtpSearch;
use backend\modules\vektor\models\PenguatkuasaanBekasPtp;
use backend\modules\vektor\models\PenguatkuasaanBekasPtpSearch;

/**
 * SrtController implements the CRUD actions for Srt model.
 */
class PtpController extends Controller
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
                        'allow' => Yii::$app->access->can('PTP-read'),
                        'actions' => ['index', 'view', 'sasaran', 'get-sasaran-form', 'bekas-view', 'liputan', 'get-ahlis', 
                        'gambar', 'print', 'penguatkuasaan', 'jenis-bekas', 'print-kompaun'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('PTP-write'),
                        'actions' => ['create', 'update', 'delete', 'sasaran', 'get-sasaran-form', 'liputan', 'bekas', 
                        'bekas-delete','file-upload', 'file-delete', 'gambar', 'print', 'penguatkuasaan', 'penguatkuasaan-view', 
                        'penguatkuasaan-delete', 'jenis-bekas', 'jenis-bekas-view', 'jenis-bekas-delete', 'print-kompaun'],
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
        $dataProvider = $searchModel->search('PTP',$this->request->queryParams);

        ActionHandler::setReturnUrl();
        //to render daftar button in index page -> go to the form ->NOR 260922
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel);
            $actionHandler->execute();
        }

        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'PTP',
            'title' => 'Pemeriksaan Tempat Pembiakan Aedes (PTP)',
            'breadCrumbs' => ['Pencegahan Vektor', 'Pemeriksaan Tempat Pembiakan Aedes (PTP)'],
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
            'idModule' => 'PTP',
            'title' => 'Pemeriksaan Tempat Pembiakan Aedes (PTP)',
            'breadCrumbs' => ['Pencegahan Vektor', ['label' => 'Pemeriksaan Tempat Pembiakan Aedes (PTP)','url' => ['index']], $model->NOSIRI, 'Paparan Maklumat'],
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
                if ($model->V_TRKHNOTIFIKASI) $model->V_TRKHNOTIFIKASI = DateTimeHelper::convert($model->V_TRKHNOTIFIKASI, true);
                if ($model->V_TRKHKEYIN) $model->V_TRKHKEYIN = DateTimeHelper::convert($model->V_TRKHKEYIN, true);

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
            'idModule' => 'PTP',
            'title' => 'Pemeriksaan Tempat Pembiakan Aedes (PTP)',
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
                if ($model->V_TRKHNOTIFIKASI) $model->V_TRKHNOTIFIKASI = DateTimeHelper::convert($model->V_TRKHNOTIFIKASI, true);
                if ($model->V_TRKHKEYIN) $model->V_TRKHKEYIN = DateTimeHelper::convert($model->V_TRKHKEYIN, true);

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
            'idModule' => 'PTP',
            'title' => 'Pemeriksaan Tempat Pembiakan Aedes (PTP)',
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

        $searchModel = new SasaranPtpSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $sasaran = new SasaranPtp();
        $sasaran->NOSIRI = $model->NOSIRI;

        if ($idsasaran) {
            $sasaran = SasaranPtp::findOne(['ID' => $idsasaran]);
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
                               
                        SasaranPtp::deleteAll(['NOSIRI' => $sasaran->NOSIRI]);

                        $sasaran->ID = Yii::$app->db->createCommand("UPDATE TBSASARAN_PTP SET ID = ROWNUM")->execute();     

                            // var_dump($sasaran->ID);
                            // exit();                   
                        
                        for ($i=0; $i < $ccount; $i++) {

                            $count = SasaranPtp::find()->count();
                            $sasaran->ID = ($count + 1);
            
                            $models = new SasaranPtp();

                            $ID[$i] = $sasaran->ID;

                            $models->ID = $ID[$i];    
                            $models->NOSIRI = $sasaran->NOSIRI;
                            $models->ID_JENISPREMIS = $sasaran->ID_JENISPREMIS[$i];
                            $models->SASARAN = $sasaran->SASARAN[$i];
                            $models->PENCAPAIAN = $sasaran->PENCAPAIAN[$i];
                            $models->JUMPOSITIF = $sasaran->JUMPOSITIF[$i];
                            $models->save(false);
                        }
            
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
                $sasaran = new SasaranPtp();           
                return $this->renderAjax('extra/_sasaran-form', [
                    'sasaran' => $sasaran,
                    'counter' => $counter,
                ]);
            } else {
                $sasaran = SasaranPtp::findOne(['NOSIRI' => $initialize]);
                $sasaran = $sasaran->premis;
                // var_dump($sasaran);
                // exit();

                if ($sasaran) {
                    $htmlOutput = [];
                    foreach ($sasaran as $key => $item) {
                        $data = [];
                        $sasaran = new SasaranPtp();                        
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
     * Manipulating Racun record
     * @param $nosiri
     * @param $idbarang
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

        $searchModel = new BekasPtpSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $bekas = new BekasPtp();
        $bekas->NOSIRI = $model->NOSIRI;

        if ($idbekas) {
            $bekas = BekasPtp::findOne(['ID' => $idbekas]);
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
                        $bekas->ID = Yii::$app->db->createCommand("UPDATE TBBEKAS_PTP SET ID = ROWNUM")->execute();     

                        $count = BekasPtp::find()->count();
                        $bekas->ID = ($count + 1);    
                    }

                    if($model->save(false) && $bekas->save()){ 
                        // if($bekas->save()){      
                        

                        if ($flag['newRecord'])
                            Yii::$app->session->setFlash('success', 'Berjaya menambah rekod Bekas Diperiksa.');
                        else
                            Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod Bekas Diperiksa.');
                        return $this->redirect(['bekas', 'nosiri' => $model->NOSIRI]);
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
        $model = BekasPtp::findOne(['ID' => $ID]);

        return $this->render('extra/bekas-view', [
            'model' => $model,
        ]);
    }

    public function actionBekasDelete()
    {
        if ($post = Yii::$app->request->post()) {
            $bekas = BekasPtp::findOne(['NOSIRI' => $post['nosiri'], 'ID' => $post['idbekas']]);
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
     * Redirect to lawatanmain/views/gambar page
     */
    public function actionGambar($nosiri)
    {
        $model = $this->findModel($nosiri);

        return $this->render('@backend/modules/lawatanmain/views/gambar', [
            'idModule' => 'PTP',
            'title' => 'Pemeriksaan Tempat Pembiakan Aedes (PTP)',
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

        /**
     * Manipulating bekas record
     * @param $nosiri
     * @param $idbekas
     * @return mixed
     */
    public function actionPenguatkuasaan($nosiri, $idpenguatkuasaan= null)
    {
        $model = $this->findModel($nosiri);
        /* for auditlog */
        $log = new LogActions;
        $tindakan = $log::ACTION_CREATE;
        $oldmodel = null;
        /* */        

        $searchModel = new PenguatkuasaanPtpSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $penguatkuasaan = new PenguatkuasaanPtp();
        $penguatkuasaan->NOSIRI = $model->NOSIRI;

        if ($idpenguatkuasaan) {
            $penguatkuasaan = PenguatkuasaanPtp::findOne(['ID' => $idpenguatkuasaan]);
            $flag['newRecord'] = false;

            $penguatkuasaan->ID = $idpenguatkuasaan;

            /* for auditlog */
            $tindakan = $log::ACTION_UPDATE;
            $oldmodel = json_encode($penguatkuasaan->getAttributes());
            /* */            
        }

        // start print list function for borang PPA - 2 -NOR10012023
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-kompaun' => function ($penguatkuasaan) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    // var_dump($sampel);
                    // exit();

                    $mpdf = new \Mpdf\Mpdf(['portrait' => 'L']);
                    $mpdf->WriteHTML($this->render('_kompaun', ['dataProvider' => $dataProvider]));
                    // $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                    $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
                },
            ]);

            $actionHandler->execute();
        }
        // end print list function
        
        if ($penguatkuasaan->load(Yii::$app->request->post()) && $penguatkuasaan) {
                        
            if($penguatkuasaan->isNewRecord){
                $penguatkuasaan->ID = Yii::$app->db->createCommand("UPDATE TBPENGUATKUASAAN_PTP SET ID = ROWNUM")->execute();     

                $count = PenguatkuasaanPtp::find()->count();
                // $count = RacunSrt::find()->max('ID'); //get last ID
                $penguatkuasaan->ID = ($count + 1);    
            }

            if ($penguatkuasaan->TRKHSALAH) $penguatkuasaan->TRKHSALAH = DateTimeHelper::convert($penguatkuasaan->TRKHSALAH, true);

            if ($penguatkuasaan->save()) {
                // record log
                 $log->recordLog($tindakan, $penguatkuasaan, $oldmodel);

                if ($flag['newRecord'])
                    Yii::$app->session->setFlash('success', 'Berjaya menambah rekod Kompaun/Notis.');
                else
                    Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod Kompaun/Notis.');
                return $this->redirect(['penguatkuasaan', 'nosiri' => $model->NOSIRI]);
            }
            // print_r($penguatkuasaan->errors);
            // exit();
        }

        return $this->render('extra/penguatkuasaan', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'penguatkuasaan' => $penguatkuasaan,
        ]);    
    }

    public function actionPenguatkuasaanView($nosiri, $ID)
    {
        $model = PenguatkuasaanPtp::findOne(['ID' => $ID]);

        return $this->render('extra/penguatkuasaan-view', [
            'model' => $model,
        ]);
    }

    public function actionPenguatkuasaanDelete()
    {
        if ($post = Yii::$app->request->post()) {
            $penguatkuasaan = PenguatkuasaanPtp::findOne(['NOSIRI' => $post['nosiri'], 'ID' => $post['idpenguatkuasaan']]);
            if ($penguatkuasaan) {
                $penguatkuasaan->delete();
                
                // record log
                // $log = new LogActions;
                // $log->recordLog($log::ACTION_DELETE, $barangRampasan);

                Yii::$app->session->setFlash('success', 'Berjaya menghapuskan rekod Kompaun/Notis ' . $penguatkuasaan->ID);
                return $this->redirect(['penguatkuasaan', 'nosiri' => $penguatkuasaan->NOSIRI]);
            }
        }
    }

    public function actionPrintKompaun($nosiri, $ID)
    {
        // $model = $this->findModel($nosiri);
        // $model = PenguatkuasaanPtp::findOne(['NOSIRI' => $nosiri, 'ID' => $idpenguatkuasaan]);
        // $model->NOSIRI = $sampel->NOSIRI;
        $model = PenguatkuasaanPtp::findOne(['ID' => $ID]);

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($this->render('_kompaun', ['model' => $model]));
        $mpdf->Output($model->NOSIRI . '.pdf', Destination::INLINE);
    }


    public function actionJenisBekas($nosiri, $nosampel, $idjenis= null)
    {
        $model = $this->findModel($nosiri);
        $sampel = PenguatkuasaanPtp::findOne(['NOSIRI' => $nosiri, 'NOSAMPEL' => $nosampel]);

        // var_dump($sampel);
        // exit();
        /* for auditlog */
        $log = new LogActions;
        $tindakan = $log::ACTION_CREATE;
        $oldmodel = null;
        /* */        

        $searchModel = new PenguatkuasaanBekasPtpSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI, $sampel->NOSAMPEL); //params should add based on what to diff on header -NOR13012023

        $flag['newRecord'] = true;
        $jenis = new PenguatkuasaanBekasPtp();
        $jenis->NOSIRI = $model->NOSIRI;
        $jenis->NOSAMPEL = $sampel->NOSAMPEL;

        if ($idjenis) {
            $jenis = PenguatkuasaanBekasPtp::findOne(['ID' => $idjenis]);
            $flag['newRecord'] = false;

            /* for auditlog */
            $tindakan = $log::ACTION_UPDATE;
            $oldmodel = json_encode($jenis->getAttributes());
            /* */            
        }

        if ($jenis->load(Yii::$app->request->post()) && $jenis) {
                        
            if($jenis->isNewRecord){
                $jenis->ID = Yii::$app->db->createCommand("UPDATE TBPENGUATKUASAAN_BEKASPTP SET ID = ROWNUM")->execute();     

                $count = PenguatkuasaanBekasPtp::find()->count();
                // $count = PenguatkuasaanBekasPtp::find()->max('ID'); //get last ID
                $jenis->ID = ($count + 1);    
            }

            if ($jenis->save()) {
                // record log
                 $log->recordLog($tindakan, $jenis, $oldmodel);

                if ($flag['newRecord'])
                    Yii::$app->session->setFlash('success', 'Berjaya menambah rekod Jenis Bekas.');
                else
                    Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod Jenis Bekas.');
                return $this->redirect(['jenis-bekas', 'nosiri' => $model->NOSIRI, 'nosampel' => $sampel->NOSAMPEL]);
            }
            // print_r($penguatkuasaan->errors);
            // exit();
        }

        return $this->render('extra/jenisbekas', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jenis' => $jenis,
        ]);
    }

    public function actionJenisBekasView($nosiri, $nosampel, $ID)
    {
        // $model = PenguatkuasaanBekasPtp::findOne(['NOSAMPEL' => $nosampel, 'ID' => $ID]);
        $model = PenguatkuasaanBekasPtp::findOne(['ID' => $ID]);

        return $this->render('extra/jenisbekas-view', [
            'model' => $model,
        ]);
    }

    public function actionJenisBekasDelete()
    {
        if ($post = Yii::$app->request->post()) {
            $jenis = PenguatkuasaanBekasPtp::findOne(['NOSIRI' => $post['nosiri'], 'NOSAMPEL' => $post['nosampel'], 'ID' => $post['idjenis']]);
            if ($jenis) {
                $jenis->delete();
                
                // record log
                // $log = new LogActions;
                // $log->recordLog($log::ACTION_DELETE, $barangRampasan);

                Yii::$app->session->setFlash('success', 'Berjaya menghapuskan rekod jenis bekas ' . $jenis->ID);
                return $this->redirect(['jenis-bekas', 'nosiri' => $jenis->NOSIRI, 'nosampel' => $jenis->NOSAMPEL]);
            }
        }
    }




   
}
