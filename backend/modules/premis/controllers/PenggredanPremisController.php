<?php

namespace backend\modules\premis\controllers;

use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanMainSearch;
use backend\modules\lawatanmain\models\LawatanPasukan;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\premis\models\TransPremis;
use backend\modules\premis\models\TransPremisSearch;
use backend\modules\vektor\models\Transtandas;
use backend\modules\vektor\models\TranstandasSearch;
use backend\modules\integrasi\models\Sewa;
use backend\modules\penyelenggaraan\models\PerkaraKomponenPrgn;

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
 * PenggredanPremisController implements the CRUD actions for PenggredanPremis model.
 */
class PenggredanPremisController extends Controller
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
                        'allow' => Yii::$app->access->can('PPM-read'),
                        'actions' => ['index', 'view', 'get-ahlis', 'lokasi-am', 'gredpremis','get-item-form','get-item-form1','kodkomponen', 'gredtandas','gambar'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('PPM-write'),
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
     * Lists all LawatanMain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LawatanMainSearch();
        $dataProvider = $searchModel->search('PPM',$this->request->queryParams);
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                
                'export-pdf' => function ($model) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('_print-grid', ['dataProvider' => $dataProvider]));
                    $mpdf->Output(Yii::$app->controller->id . ' PemeriksaanPremis.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                },
            ]);
            $actionHandler->execute();
        }
        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'PPM',
            'title' => 'Pemeriksaan Premis',
            'breadCrumbs' => ['Premis Makanan', 'Pemeriksaan Premis'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
    }

    /**
     * Displays a single LawatanMain model.
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
            'idModule' => 'PPM',
            'title' => 'Pemeriksaan Premis',
            'breadCrumbs' => ['Premis Makanan', ['label' => 'Pemeriksaan Premis', 'url' => ['index']], $model->NOSIRI,'Paparan Maklumat'],
            'model' => $model,
        ]);
    }
    /**
     * Creates a new PenggredanPremis model.
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
                // var_dump($model);
                // exit;
                // var_dump('hai');
                // exit;
                $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
                $model->IDZON_AM = $idzon_amarr[0];
                $model->PRGNLOKASI_AM = $idzon_amarr[1];
                $model->IDSUBUNIT = Yii::$app->user->identity->SUBUNIT;

                // var_dump(implode(',', $model->JENISTANDAS_ARR));
                // exit;

                // $jenistandas = implode(',', $model->JENISTANDAS_ARR); //implode return string from array
                $model->PTS_JENISTANDAS =  implode(',', $model->PTS_JENISTANDAS); //save jenis tandas
                $model->BILTANDAS =  implode(',', $model->BILTANDAS); //save bilangan tandas
                
                $model->setNosiri('PPM');  //set No Siri.
              
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
            'idModule' => 'PPM',
            'titleMain' => 'Penggredan Premis',
            'title' => 'Penggredan Premis',
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing LawatanMain model.
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

            if ($model->load(Yii::$app->request->post())) {
                // $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
                // $model->IDZON_AM = $idzon_amarr[0];
                // $model->PRGNLOKASI_AM = $idzon_amarr[1];
                
                $model->PTS_JENISTANDAS =  implode(',', $model->PTS_JENISTANDAS); //save jenis tandas
                $model->BILTANDAS =  implode(',', $model->BILTANDAS);
                
                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);
                $model->PGNDAFTAR = Yii::$app->user->ID;
                $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));

                if ($model->save(false)) {
                    
                    Transtandas::deleteAll(['NOSIRI' => $model->NOSIRI]);
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
            'idModule' => 'PPM',
            'titleMain' => 'Penggredan Premis',
            'title' => 'Penggredan Premis',
            'breadCrumbs' => ['Penggredan Premis Makanan', 'Penggredan Tandas', $model->NOSIRI],
            'model' => $model,
        ]);
    }

    
    /**
     * Finds the LawatanMain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id Nosiri
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
     * Tabmenu Penggredan Premis.
     * Redirect to Penggedan Premis index page.
     * Save and update record.
     * @param $nosiri
     */
    public function actionGredpremis($nosiri, $ID = null)
    {

        $model = $this->findModel($nosiri);
        
        $searchModel = new TranspremisSearch();
        $dataProvider = $searchModel->searchkomponen(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $gredpremis = new Transpremis();
        $gredpremis->NOSIRI = $model->NOSIRI;
        $gredpremis->IDMODULE = $model->IDMODULE;
        // $gredpremis->jumlahmarkah = '';
        // $gredpremis->gred = '';
        // var_dump($gredpremis->NOSIRI);
        //     exit();

        //for update data
        if ($ID) {
            $gredpremis = Transpremis::findOne(['ID' => $ID]);
            $flag['newRecord'] = false;

        }

        // $searchModel = new PenggredanPremisSearch();
        // $dataProvider = $searchModel->search($this->request->queryParams);
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-pdf' => function ($gredpremis) use ($dataProvider) {
                    $dataProvider->setPagination(false);
                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('extra/_printpremis', ['dataProvider' => $dataProvider, 'gredpremis' => $gredpremis]));
                    $mpdf->Output(Yii::$app->controller->id . 'Maklumat Premis.pdf', \Mpdf\Output\Destination::INLINE);
                },
            ]);

            $actionHandler->execute();
        }        



        //save data 
        if (Yii::$app->request->post()) {   //zihan 20221021
            if ($gredpremis->load(Yii::$app->request->post())) {

                // $ccount = count($gredpremis->KODPERKARA);
                $ccount = count($gredpremis->CHKITEM);      //selected checkbox lists
      
                Transpremis::deleteAll(['NOSIRI' => $gredpremis->NOSIRI]);
                for ($i = 0; $i < $ccount; $i++) {
                    $models = new Transpremis();
                    
                    if (isset($gredpremis->CHKITEM[$i])) {

                        $listno = $gredpremis->CHKITEM[$i] - 1;     //get the correct index tobe assign in array
                        
                        $models->NOSIRI = $gredpremis->NOSIRI;
                        $models->IDMODULE = $gredpremis->IDMODULE;
                        $models->KODPERKARA = $gredpremis->KODPERKARA[$listno];
                        $models->KODKOMPONEN = $gredpremis->KODKOMPONEN[$listno];
                        $models->KODPRGN = $gredpremis->KODPRGN[$listno];
                        $models->MARKAH = $gredpremis->MARKAH[$listno];
                        $models->DEMERIT = $gredpremis->DEMERIT[$listno];
                        $models->CATATAN = $gredpremis->CATATAN[$listno];
                        $models->PGNDAFTAR = Yii::$app->user->ID;
                        $models->TRKHDAFTAR = strtotime(date("Y-m-d H:i:s"));
                        $models->PGNAKHIR = Yii::$app->user->ID;
                        $models->TRKHAKHIR = strtotime(date("Y-m-d H:i:s"));
                        $models->save(false);
                    }
                }
                
                //tak perlu {} kalau ada 1 line process
                if ($flag['newRecord'])
                    Yii::$app->session->setFlash('success', 'Berjaya membuat Penggredan Premis.');
                else
                    Yii::$app->session->setFlash('success', 'Berjaya mengemaskini Penggredan Premis.');
                return $this->redirect(['gredpremis', 'nosiri' => $model->NOSIRI]);
        
            }
        }

        return $this->render('extra/gredpremis', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gredpremis' => $gredpremis,
        ]);
    }


    public function actionGetItemForm($counter, $initialize = false, $checkall = false)
    {
        // var_dump($checkall);
        // exit;
        if (Yii::$app->request->isAjax) {

            $query ="SELECT A.KODPERKARA, C.PRGN AS PERKARAPRGN, A.KODKOMPONEN, B.PRGN AS KOMPONENPRGN,A.KODPRGN, A.PRGN AS PRGN,
            A.MARKAH, D.DEMERIT, D.CATATAN, D.NOSIRI
            FROM TBPP_PERKARA_KOMPONEN_PRGN A
            LEFT JOIN TBPP_PERKARA_KOMPONEN B ON A.KODPERKARA=B.KODPERKARA AND A.KODKOMPONEN=B.KODKOMPONEN AND B.STATUS=1 AND B.JENIS=1
            LEFT JOIN  TBPP_PERKARA C ON A.KODPERKARA=C.KODPERKARA AND C.STATUS=1 AND C.JENIS=1
            LEFT JOIN TBGRED_PREMIS D ON  D.NOSIRI = '$initialize' AND D.STATUS=1 AND D.KODPERKARA=A.KODPERKARA AND D.KODKOMPONEN=A.KODKOMPONEN AND D.KODPRGN=A.KODPRGN
            WHERE A.STATUS=1 AND A.JENIS=1 ORDER BY A.KODPERKARA,A.KODKOMPONEN, A.KODPRGN";

            $gredpremis =  Yii::$app->db->createCommand($query)->queryAll();

            //   var_dump($query);
            //         exit();

            //display data on textbox
            if ($gredpremis) {
                // var_dump($item['KODPERKARA']);
                    // exit();

                $i = 0;
                $htmlOutput = [];
                foreach ($gredpremis as $key => $item) { 
             
                    $data = [];
                    $gredpremis = new Transpremis();

                    // $gredpremis->NOSIRI = $model->NOSIRI;
                    // $gredpremis->IDMODULE = $model->IDMODULE;

                    // var_dump($item['KODPERKARA']);
                    // exit();
         
                    $data['CHKITEM'] = $i;     
                    $data['KODPERKARA'] = $item['KODPERKARA'];
                    $data['PERKARAPRGN'] = $item['PERKARAPRGN'];
                    $data['KODKOMPONEN'] =$item['KODKOMPONEN'];    
                    $data['KOMPONENPRGN'] = $item['KOMPONENPRGN'];
                    $data['KODPRGN'] = $item['KODPRGN'];
                    $data['PRGN'] = $item['PRGN'];
                    $data['MARKAH'] = $item['MARKAH'];
                    $data['CATATAN'] = $item['CATATAN'];
                    $data['DEMERIT'] = $item['DEMERIT'];
                    $data['ISEXIST'] = $item['NOSIRI']; //ZIHAN TO CHECK EXISTENCE RECORD

                    $i++;
                    $htmlOutput[] = $this->renderAjax('extra/premis_itemform', [
                        'gredpremis' => $gredpremis,
                        'counter' => ($key + 1),
                        'tmpData' => $data,
                        'nosiri' => $initialize,
                        'checkall' => $checkall,    //zihan
                    ]);
                }
                if (count($htmlOutput) > 0) return implode(null, $htmlOutput);
            }

        }
        return null;
    }


    public function actionGredtandas($nosiri, $ID = null)
    {
        $model = $this->findModel($nosiri);
        
        $searchModel = new TranstandasSearch();
        $dataProvider = $searchModel->searchkomponen(Yii::$app->request->queryParams, $model->NOSIRI);
        $flag['newRecord'] = true;

        if ($ID) {
            $gredtandas = Transtandas::findOne(['ID' => $ID]);
            $flag['newRecord'] = false;
        }
 
        if ($nosiri) {
            $gredtandas = Transtandas::findOne(['NOSIRI' => $nosiri]);

            if ($gredtandas == null){
                // $flag['newRecord'] = true;
                $gredtandas = new Transtandas();
                $gredtandas->NOSIRI = $model->NOSIRI;
                $gredtandas->IDMODULE = $model->IDMODULE;
            }
            
        }

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($gredtandas, [
                'export-pdf' => function ($gredtandas) use ($dataProvider) {
                    $dataProvider->setPagination(false);
                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('@backend/modules/vektor/views/tandas/extra/_printtandas', ['dataProvider' => $dataProvider, 'gredtandas' => $gredtandas]));
                    $mpdf->Output(Yii::$app->controller->id . 'Maklumat Premis.pdf', \Mpdf\Output\Destination::INLINE);
                },
            ]);

            $actionHandler->execute();  
        } 
                
        //save data 
        if ($gredtandas->load(Yii::$app->request->post())) {
            // var_dump($gredtandas);
            // exit;
            // $ccount = count($gredtandas->KODPERKARA);
            $ccount = count($gredtandas->CHKITEM);      //selected checkbox lists

            Transtandas::deleteAll(['NOSIRI' => $gredtandas->NOSIRI]);
            for ($i = 0; $i < $ccount; $i++) {
                $models = new Transtandas();

                if (isset($gredtandas->CHKITEM[$i])) {

                    $listno = $gredtandas->CHKITEM[$i] - 1;     //get the correct index tobe assign in array

                    $models->NOSIRI = $gredtandas->NOSIRI;
                    $models->IDMODULE = $gredtandas->IDMODULE;
                    $models->KATPREMIS = $gredtandas->KATPREMIS;
                    $models->KODPERKARA = $gredtandas->KODPERKARA[$listno];
                    $models->KODKOMPONEN = $gredtandas->KODKOMPONEN[$listno];
                    $models->KODPRGN = $gredtandas->KODPRGN[$listno];
                    // var_dump($models->KATPREMIS);
                    // exit;
                    $models->MARKAH = $gredtandas->MARKAH[$listno];
                    $models->ML = $gredtandas->ML[$listno];
                    $models->MW = $gredtandas->MW[$listno];
                    $models->MO = $gredtandas->MO[$listno];
                    $models->MU = $gredtandas->MU[$listno];
                    $models->MK = $gredtandas->MK[$listno];
                    $models->PGNDAFTAR = Yii::$app->user->ID;
                    $models->TRKHDAFTAR = strtotime(date("Y-m-d H:i:s"));
                    $models->PGNAKHIR = Yii::$app->user->ID;
                    $models->TRKHAKHIR = strtotime(date("Y-m-d H:i:s"));
                    $models->save(false);
                }
            }

            //tak perlu {} kalau ada 1 line process
            if ($flag['newRecord'])
                Yii::$app->session->setFlash('success', 'Berjaya membuat Penggredan Tandas.');
            else
                Yii::$app->session->setFlash('success', 'Berjaya mengemaskini Penggredan Tandas.');
            return $this->redirect(['gredtandas', 'nosiri' => $model->NOSIRI]);
       
        }

        return $this->render('@backend/modules/vektor/views/tandas/extra/gredtandas', [
            'title' => 'Penggredan Tandas',
            'breadCrumbs' => ['Penggredan Premis Makanan', 'Penggredan Tandas', $model->NOSIRI],
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gredtandas' => $gredtandas,
        ]);
    }


    /**
     * Tabmenu Penggredan Premis.
     * Redirect to page Gambar at LawatanMain.
    */
    public function actionGambar($nosiri)
    {
        $model = $this->findModel($nosiri);

        return $this->render('@backend/modules/lawatanmain/views/gambar', [
            'idModule' => 'PPM',
            'title' => 'Pemeriksaan Premis',
            'titleMain' => 'Premis Makanan',
            'model' => $model,
        ]);
    }

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

    
}
