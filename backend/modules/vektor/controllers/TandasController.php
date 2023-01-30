<?php

namespace backend\modules\vektor\controllers;

use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanMainSearch;
use backend\modules\lawatanmain\models\LawatanPasukan;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\vektor\models\TransTandas;
use backend\modules\vektor\models\TransTandasSearch;
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
 * TandasController implements the CRUD actions for LawatanMain model.
 */
class TandasController extends Controller
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
                        'allow' => Yii::$app->access->can('PTS-read'),
                        'actions' => ['index', 'view', 'get-ahlis', 'lokasi-am', 'gredvektor','get-item-form','get-item-form1','kodkomponen', 'gredtandas','gambar', 'get-jenis-tandas', 'get-jenis' ],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('PTS-write'),
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
        $dataProvider = $searchModel->search('PTS', $this->request->queryParams);
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                
                'export-pdf' => function ($model) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('_print-grid', ['dataProvider' => $dataProvider]));
                    $mpdf->Output(Yii::$app->controller->id . ' PemeriksaanTandas.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                },
            ]);
            $actionHandler->execute();
        }
        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'PTS',
            'title' => 'Pemeriksaan Tandas',
            'breadCrumbs' => ['Pencegahan Vektor', 'Pemeriksaan Tandas'],
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
            'idModule' => 'PTS',
            'title' => 'Pemeriksaan Tandas',
            'breadCrumbs' => ['Pencegahan Vektor', ['label'=>'Pemeriksaan Tandas', 'url'=> ['index']], $model->NOSIRI, 'Paparan Maklumat'],
            'model' => $model,
        ]);
    }
    /**
     * Creates a new PenggredanTandas model.
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

                $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
                $model->IDZON_AM = $idzon_amarr[0];
                $model->PRGNLOKASI_AM = $idzon_amarr[1];

                
                
                $model->PTS_JENISTANDAS =  implode(',', $model->PTS_JENISTANDAS); //save jenis tandas
                // var_dump($model->PTS_JENISTANDAS);
                // exit;
                // var_dump($model->PTS_JENISTANDAS);
                // exit;
                $model->BILTANDAS =  implode(',', $model->BILTANDAS); //save bilangan tandas

                
                // var_dump($idzon_amarr);
               
                $model->setNosiri('PTS'); //set No Siri.      
                // $model->IDSUBUNIT = Yii::$app->user->identity->SUBUNIT;

                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);
                $model->PGNDAFTAR = Yii::$app->user->ID;
                $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));
            //     print_r($model->errors);
            // exit();
                if ($model->save(false)) {

                      
                    $model->saveMaklumatPemilik(); //save maklumat lesen
                    $model->saveAhliPasukan(); //save maklumat pasukan
                    // var_dump($model);
                    // exit;
                    // record log
                    // $log = new LogActions;
                    // $log->recordLog($log::ACTION_CREATE, $model);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
            // print_r($model->errors);
            // exit();
        }

        return $this->render('@backend/modules/lawatanmain/views/create', [
            'idModule' => 'PTS',
            'titleMain' => 'Pencegahan Vektor',
            'title' => 'Pemeriksaan Tandas',
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing LawatanMain model.
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

                // $idzon_amarr = explode('-', $model->PRGNLOKASI_AM);     //$model->PRGNLOKASI_AM from form return string, example = "1-BANDAR SRI DAMANSARA PJU 9", need to split
                // $model->IDZON_AM = $idzon_amarr[0];
                // $model->PRGNLOKASI_AM = $idzon_amarr[1];

                $model->PTS_JENISTANDAS =  implode(',', $model->PTS_JENISTANDAS); //save jenis tandas
                $model->BILTANDAS =  implode(',', $model->BILTANDAS); //save bilangan tandas

                if ($model->TRKHMULA) $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, true);
                if ($model->TRKHTAMAT) $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, true);
                $model->PGNDAFTAR = Yii::$app->user->ID;
                $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));

                if ($model->save(false)) {
                    
                    // record log
                    // Transtandas::deleteAll(['NOSIRI' => $model->NOSIRI]);
                    $model->saveMaklumatPemilik(); //save maklumat lesen
                    $model->saveAhliPasukan(); //save maklumat pasukan
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }
        return $this->render('@backend/modules/lawatanmain/views/update', [
            'idModule' => 'PTS',
            'titleMain' => 'Pencegahan Vektor',
            'title' => 'Pemeriksaan Tandas',
            'model' => $model,
        ]);
    }

    
    /**
     * Finds the PenggredanTandas model based on its primary key value.
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
                $gredtandas = new Transtandas();
                $gredtandas->NOSIRI = $model->NOSIRI;
                $gredtandas->IDMODULE = $model->IDMODULE;
            }else{
                $flag['newRecord'] = false;
            }
            
        }

        
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($gredtandas, [
                'export-pdf' => function ($gredtandas) use ($dataProvider) {
                    $dataProvider->setPagination(false);
                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('extra/_printtandas', ['dataProvider' => $dataProvider, 'gredtandas' => $gredtandas]));
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
            // var_dump($gredtandas->JUM_MARKAH);
            // exit;
            $ccount = count($gredtandas->CHKITEM);      //selected checkbox lists

            Transtandas::deleteAll(['NOSIRI' => $gredtandas->NOSIRI]);
            for ($i = 0; $i < $ccount; $i++) {
                $models = new Transtandas();

                if (isset($gredtandas->CHKITEM[$i])) {

                    $listno = $gredtandas->CHKITEM[$i] - 1;     //get the correct index tobe assign in array
                    // var_dump($gredtandas->JUM_MARKAH);
                    // exit;
                    $models->NOSIRI = $gredtandas->NOSIRI;
                    $models->IDMODULE = $gredtandas->IDMODULE;
                    $models->KATPREMIS = $gredtandas->KATPREMIS;
                    $models->KODPERKARA = $gredtandas->KODPERKARA[$listno];
                    $models->KODKOMPONEN = $gredtandas->KODKOMPONEN[$listno];
                    $models->KODPRGN = $gredtandas->KODPRGN[$listno];
                    $models->MARKAH = $gredtandas->MARKAH[$listno];
                    $models->ML = $gredtandas->ML[$listno];
                    $models->MW = $gredtandas->MW[$listno];
                    $models->MO = $gredtandas->MO[$listno];
                    $models->MU = $gredtandas->MU[$listno];
                    $models->MK = $gredtandas->MK[$listno];
                    $models->JUM_MARKAH = $gredtandas->JUM_MARKAH[$listno];
                    // var_dump($gredtandas->JUM_MARKAH);
                    // exit;
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

        return $this->render('extra/gredtandas', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gredtandas' => $gredtandas,
        ]);
    }

    public function actionGetItemForm($counter, $initialize = false, $checkall = false, $pts_jenistandas)
    {

        // $models = Transtandas::findOne(['NOSIRI' => $initialize]);

        if (Yii::$app->request->isAjax) {

            $query="SELECT A.KODPERKARA, C.PRGN AS PERKARAPRGN, A.KODKOMPONEN, B.PRGN AS KOMPONENPRGN,
            A.KODPRGN, A.PRGN AS PRGN, A.MARKAH, D.ML, D.MW, D.MO, D.MU,D.MK, D.NOSIRI, D.JUM_MARKAH
            FROM TBPP_PERKARA_KOMPONEN_PRGN A
            LEFT JOIN TBPP_PERKARA_KOMPONEN B ON A.KODPERKARA=B.KODPERKARA AND A.KODKOMPONEN=B.KODKOMPONEN AND B.STATUS=1 AND B.JENIS=2
            LEFT JOIN  TBPP_PERKARA C ON A.KODPERKARA=C.KODPERKARA AND C.STATUS=1 AND C.JENIS=2
            LEFT JOIN TBGRED_TANDAS D ON  D.NOSIRI = '$initialize' AND D.STATUS=1 AND D.KODPERKARA=A.KODPERKARA AND D.KODKOMPONEN=A.KODKOMPONEN AND D.KODPRGN=A.KODPRGN
            WHERE A.STATUS=1 AND A.JENIS=2 ORDER BY A.KODPERKARA, A.KODPRGN";

            $gredtandas =  Yii::$app->db->createCommand($query)->queryAll();

            //display data on textbox
            if ($gredtandas) {

                // $gredtandas = Transtandas::findOne(['NOSIRI' => $initialize]);
                //  var_dump($models);
                // exit();
                $i = 0;
                $htmlOutput = [];
                foreach ($gredtandas as $key => $item) { 
             
                    $data = [];
                    $gredtandas = new Transtandas();
                 
                    $data['CHKITEM'] = $i; 
                    $data['KODPERKARA'] = $item['KODPERKARA'];
                    $data['PERKARAPRGN'] = $item['PERKARAPRGN'];
                    $data['KODKOMPONEN'] =$item['KODKOMPONEN'];    
                    $data['KOMPONENPRGN'] = $item['KOMPONENPRGN'];
                    $data['KODPRGN'] = $item['KODPRGN'];
                    $data['PRGN'] = $item['PRGN'];
                    $data['MARKAH'] = $item['MARKAH'];

                    $data['ML'] = $item['ML'];
                    $data['MW'] = $item['MW'];
                    $data['MO'] = $item['MO'];
                    $data['MU'] = $item['MU'];
                    $data['MK'] = $item['MK'];
                    $data['ISEXIST'] = $item['NOSIRI']; //to check exist existence record
                    $data['JUM_MARKAH'] = $item['JUM_MARKAH'];
                    // $data['ISEXIST'] = $item['NOSIRI'];
                    // $data['totalrow'] = $item->markahtandas[$i]['totalrow'];

                    // var_dump($item->markahtandas['total']);
                    // exit;
                     
                    $i++;
                    
                    $htmlOutput[] = $this->renderAjax('extra/tandas_itemform', [
                        'gredtandas' => $gredtandas,
                        'counter' => ($key + 1),
                        'tmpData' => $data,
                        'nosiri' => $initialize,
                        'checkall' => $checkall,
                        'pts_jenistandas'=> $pts_jenistandas,
                    ]);
                }
                if (count($htmlOutput) > 0) return implode(null, $htmlOutput);
            }
        }
        return null;
    }

  
    public function actionGetJenisTandas($counter, $initialize = false)
    {
        if (Yii::$app->request->isAjax) {
    
            if (!$initialize) {
                $model = new LawatanMain();
                return $this->renderAjax('jenistandas_itemform', [
                    'model' => $model,
                    'counter' => $counter,
                ]);
            } else {
                $model = LawatanMain::findOne(['NOSIRI' => $initialize]);
                $model = $model->countertandas;

                if ($model) {
                    $htmlOutput = [];

                    foreach ($model as $key => $item) {
                        $jenistandas = explode(',', $item->PTS_JENISTANDAS); //convert STRING to ARRAY
                        //array(2) { [0]=> string(1) "1" [1]=> string(1) "6" }
                        $biltandas = explode(',', $item->BILTANDAS); //convert STRING to ARRAY
    
                        for ($i=0; $i < count($jenistandas); $i++) {
                      
                            $data = [];
                            $model = new LawatanMain();
                            $data['JENISTANDAS'] = $jenistandas[$i];
                            $data['BILTANDAS'] = $biltandas[$i];
                            
                            //var_dump($i);
                            //var_dump($data['JENISTANDAS']);

                            $htmlOutput[] = $this->renderAjax('jenistandas_itemform', [
                                'model' => $model,
                                'counter' => $i,
                                'tmpData' => $data,
                            ]);
                        }   
                    }
                    if (count($htmlOutput) > 0) return implode(null, $htmlOutput);
                }
            }
        }
        return null;
    }

    

      /**
     * Redirect to lawatanmain/views/gambar page
     */
    public function actionGambar($nosiri)
    {
        $model = $this->findModel($nosiri);

        return $this->render('@backend/modules/lawatanmain/views/gambar', [
            'idModule' => 'PTS',
            'titleMain' => 'Pencegahan Vektor',
            'title' => 'Pemeriksaan Tandas',
            'model' => $model,
        ]);
    }
}
