<?php

namespace backend\modules\vektor\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;   
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\components\LogActions;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanMainSearch;
use backend\modules\vektor\models\SasaranUlv;
use backend\modules\vektor\models\SasaranUlvSearch;

/**
 * SrtController implements the CRUD actions for Srt model.
 */
class UlvController extends Controller
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
                        'allow' => Yii::$app->access->can('ULV-read'),
                        'actions' => ['index', 'view', 'liputan', 'get-liputan-form', 'get-ahlis', 'gambar', 'print'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('ULV-write'),
                        'actions' => ['create', 'update', 'delete', 'liputan', 'get-liputan-form', 'file-upload', 'file-delete', 
                        'gambar', 'print'],
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
        $dataProvider = $searchModel->search('ULV',$this->request->queryParams);

        ActionHandler::setReturnUrl();
        //to render daftar button in index page -> go to the form ->NOR 260922
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel);
            $actionHandler->execute();
        }

        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'ULV',
            'title' => 'Semburan Kabus Mesin (ULV)',
            'breadCrumbs' => ['Pencegahan Vektor', 'Semburan Kabus Mesin (ULV)'],
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
            'idModule' => 'ULV',
            'title' => 'Semburan Kabus Mesin (ULV)',
            'breadCrumbs' => ['Pencegahan Vektor', ['label'=>'Semburan Kabus Mesin (ULV)', 'url' => ['index']], $model->NOSIRI, 'Paparan Maklumat'],
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
                if ($model->ULV_TRKHONSET) $model->ULV_TRKHONSET = DateTimeHelper::convert($model->ULV_TRKHONSET, true);
                if ($model->V_TRKHKEYIN) $model->V_TRKHKEYIN = DateTimeHelper::convert($model->V_TRKHKEYIN, true);
                if ($model->V_TRKHNOTIFIKASI) $model->V_TRKHNOTIFIKASI = DateTimeHelper::convert($model->V_TRKHNOTIFIKASI, true);
                //nor10112022
                if ($model->ULV_MASAMULAHUJAN) $model->ULV_MASAMULAHUJAN = DateTimeHelper::convert($model->ULV_MASAMULAHUJAN, true); 
                if ($model->ULV_MASATAMATHUJAN) $model->ULV_MASATAMATHUJAN = DateTimeHelper::convert($model->ULV_MASATAMATHUJAN, true); 
                if ($model->ULV_MASAMULAANGIN) $model->ULV_MASAMULAANGIN = DateTimeHelper::convert($model->ULV_MASAMULAANGIN, true); 
                if ($model->ULV_MASATAMATANGIN) $model->ULV_MASATAMATANGIN = DateTimeHelper::convert($model->ULV_MASATAMATANGIN, true); 

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
            'idModule' => 'ULV',
            'title' => 'Semburan Kabus Mesin (ULV)',
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
                if ($model->ULV_TRKHONSET) $model->ULV_TRKHONSET = DateTimeHelper::convert($model->ULV_TRKHONSET, true);
                if ($model->V_TRKHKEYIN) $model->V_TRKHKEYIN = DateTimeHelper::convert($model->V_TRKHKEYIN, true);
                if ($model->V_TRKHNOTIFIKASI) $model->V_TRKHNOTIFIKASI = DateTimeHelper::convert($model->V_TRKHNOTIFIKASI, true);
                //nor10112022
                if ($model->ULV_MASAMULAHUJAN) $model->ULV_MASAMULAHUJAN = DateTimeHelper::convert($model->ULV_MASAMULAHUJAN, true); 
                if ($model->ULV_MASATAMATHUJAN) $model->ULV_MASATAMATHUJAN = DateTimeHelper::convert($model->ULV_MASATAMATHUJAN, true); 
                if ($model->ULV_MASAMULAANGIN) $model->ULV_MASAMULAANGIN = DateTimeHelper::convert($model->ULV_MASAMULAANGIN, true); 
                if ($model->ULV_MASATAMATANGIN) $model->ULV_MASATAMATANGIN = DateTimeHelper::convert($model->ULV_MASATAMATANGIN, true); 

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
            'idModule' => 'ULV',
            'title' => 'Semburan Kabus Mesin (ULV)',
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
    public function actionLiputan($nosiri, $idliputan= null)
    {
        $model = $this->findModel($nosiri);

        /* for auditlog */
        // $log = new LogActions;
        // $tindakan = $log::ACTION_CREATE;
        // $oldmodel = null;
        /* */        

        $searchModel = new SasaranUlvSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $liputan = new SasaranUlv();
        $liputan->NOSIRI = $model->NOSIRI;

        if ($idliputan) {
            $liputan = SasaranUlv::findOne(['ID' => $idliputan]);
            $flag['newRecord'] = false;

            /* for auditlog */
            // $tindakan = $log::ACTION_UPDATE;
            // $oldmodel = json_encode($sampel->getAttributes());
            /* */            
        }

        if ($liputan->load(Yii::$app->request->post())) {        

            $ccount = count($liputan->ID_JENISPREMIS);

            // var_dump($ccount);
            // exit();

            $ID[] = null;

            SasaranUlv::deleteAll(['NOSIRI' => $liputan->NOSIRI]);

            $liputan->ID = Yii::$app->db->createCommand("UPDATE TBSASARAN_ULV SET ID = ROWNUM")->execute();     
            
            for ($i=0; $i < $ccount; $i++) {

                // $count = SasaranUlv::find()->where(['NOSIRI' => $liputan->NOSIRI])->count(); will count based on nosiri. will restart at 1 for different nosiri -NOR11102022
                $count = SasaranUlv::find()->count();
                $liputan->ID = ($count + 1);


                $models = new SasaranUlv();

                $ID[$i] = $liputan->ID;

                $models->ID = $ID[$i];
                $models->NOSIRI = $liputan->NOSIRI;
                $models->ID_JENISPREMIS = $liputan->ID_JENISPREMIS[$i];
                $models->SASARAN1 = $liputan->SASARAN1[$i];
                $models->PENCAPAIAN1 = $liputan->PENCAPAIAN1[$i];
                $models->SASARAN2 = $liputan->SASARAN2[$i];
                $models->PENCAPAIAN2 = $liputan->PENCAPAIAN2[$i];
                $models->save(false);
            }

            if ($flag['newRecord'])
                Yii::$app->session->setFlash('success', 'Berjaya menambah rekod liputan semburan.');
            else
                Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod liputan semburan.');
            return $this->redirect(['liputan', 'nosiri' => $model->NOSIRI]);
            
            print_r($liputan->errors);
            exit;
        }

        return $this->render('extra/liputan', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'liputan' => $liputan,
        ]);
    }

    public function actionGetLiputanForm($counter, $initialize = false)
    {
        if (Yii::$app->request->isAjax) {

            if (!$initialize) {
                $liputan = new SasaranUlv();           
                return $this->renderAjax('extra/_liputan-form', [
                    'liputan' => $liputan,
                    'counter' => $counter,
                ]);
            } else {
                $liputan = SasaranUlv::findOne(['NOSIRI' => $initialize]);
                $liputan = $liputan->premis;
                // var_dump($liputan);
                // exit();

                if ($liputan) {
                    $htmlOutput = [];
                    foreach ($liputan as $key => $item) {
                        $data = [];
                        $liputan = new SasaranUlv();                        
                        $data['ID_JENISPREMIS'] = $item->ID_JENISPREMIS;
                        $data['SASARAN1'] = $item->SASARAN1;
                        $data['PENCAPAIAN1'] = $item->PENCAPAIAN1;
                        $data['SASARAN2'] = $item->SASARAN2;
                        $data['PENCAPAIAN2'] = $item->PENCAPAIAN2;

                        $htmlOutput[] = $this->renderAjax('extra/_liputan-form', [
                        'liputan' => $liputan,
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
     * Redirect to lawatanmain/views/gambar page
     */
    public function actionGambar($nosiri)
    {
        $model = $this->findModel($nosiri);

        return $this->render('@backend/modules/lawatanmain/views/gambar', [
            'idModule' => 'ULV',
            'title' => 'Semburan Kabus Mesin (ULV)',
            'titleMain' => 'Pencegahan Vektor',
            'model' => $model,
        ]);
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

    public function actionPrint($id)
    {
        $model = $this->findModel($id);

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($this->render('_print', ['model' => $model]));
        $mpdf->Output($model->NOSIRI . '.pdf', Destination::INLINE);
    }

}
