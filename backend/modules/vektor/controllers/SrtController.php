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

use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanMainSearch;
use backend\modules\vektor\models\SasaranSrt;
use backend\modules\vektor\models\SasaranSrtSearch;
use backend\modules\vektor\models\RacunSrt;
use backend\modules\vektor\models\RacunSrtSearch;
use backend\modules\vektor\models\TransLiputansrt;
use backend\modules\vektor\models\TransLiputansrtSearch;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
/**
 * SrtController implements the CRUD actions for Srt model.
 */
class SrtController extends Controller
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
                        'allow' => Yii::$app->access->can('SRT-read'),
                        'actions' => ['index', 'view', 'sasaran', 'get-sasaran-form', 'racun-view', 'liputan', 'get-ahlis',
                        'print', 'gambar'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('SRT-write'),
                        'actions' => ['create', 'update', 'delete', 'sasaran', 'get-sasaran-form', 'liputan', 'racun', 
                        'racun-delete','file-upload', 'file-delete', 'gambar', 'print'],
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
     * Display data based on idmodule SRT.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LawatanMainSearch();
        $dataProvider = $searchModel->search('SRT', $this->request->queryParams);

        // var_dump($dataProvider);
        // exit();

        ActionHandler::setReturnUrl();
        //to render daftar button in index page -> go to the form ->NOR 260922
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel);
            $actionHandler->execute();
        }

        return $this->render('@backend/modules/lawatanmain/views/index', [
            'idModule' => 'SRT',
            'title' => 'Semburan Termal (SRT)',
            'breadCrumbs' => ['Pencegahan Vektor', 'Semburan Termal (SRT)'],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single data of Semburan Termal .
     * @param string $id NOSIRI
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
            'idModule' => 'SRT',
            'title' => 'Semburan Termal (SRT)',
            'breadCrumbs' => ['Pencegahan Vektor', ['label'=> 'Semburan Termal (SRT)', 'url' => ['index']], $model->NOSIRI, 'Paparan Maklumat'],
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

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();
            
            if ($action && $model->load(Yii::$app->request->post())) {

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

                    $model->saveAhliPasukan(); //save maklumat pasukan

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
            'idModule' => 'SRT',
            'titleMain' => 'Pencegahan Vektor',
            'title' => 'Semburan Termal (SRT)',
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Handswab model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id NOSIRI
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
            'idModule' => 'SRT',
            'title' => 'Semburan Termal (SRT)',
            'titleMain' => 'Pencegahan Vektor',
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Lawatan Main model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id NOSIRI
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
     * @param string $id NOSIRI
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

        /* for auditlog */
        $log = new LogActions;
        $tindakan = $log::ACTION_CREATE;
        $oldmodel = null;
        /* */        

        $searchModel = new SasaranSrtSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        // $flag['newRecord'] = false;
        $sasaran = new SasaranSrt();
        $sasaran->NOSIRI = $model->NOSIRI;

        //if the data exists, it will go this function (update form)
        if ($idsasaran) {
            $sasaran = SasaranSrt::findOne(['ID' => $idsasaran]);

            /* for auditlog */
            $tindakan = $log::ACTION_UPDATE;
            $oldmodel = json_encode($sasaran->getAttributes());
            /* */            
        }

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {

                if ($sasaran->load(Yii::$app->request->post())) {

                    if($model->save(false)){
                        $ccount = count($sasaran->ID_JENISPREMIS);

                        $ID[] = null;
        
                        SasaranSrt::deleteAll(['NOSIRI' => $sasaran->NOSIRI]);

                        $sasaran->ID = Yii::$app->db->createCommand("UPDATE TBSASARAN_SRT SET ID = ROWNUM")->execute();      
                        
                        for ($i=0; $i < $ccount; $i++) {

                            $count = SasaranSrt::find()->count();
                            $sasaran->ID = ($count + 1);
            
                            $models = new SasaranSrt();

                            $ID[$i] = $sasaran->ID;

                            $models->ID = $ID[$i];
                            $models->NOSIRI = $sasaran->NOSIRI;
                            $models->ID_JENISPREMIS = $sasaran->ID_JENISPREMIS[$i];
                            $models->JUMPREMIS = $sasaran->JUMPREMIS[$i];
                            $models->PENCAPAIAN1 = $sasaran->PENCAPAIAN1[$i];
                            $models->PENCAPAIAN2 = $sasaran->PENCAPAIAN2[$i];
                            $models->save(false);
                        }

                        // var_dump($flag['newRecord']);
                        // exit;
                        // if ($flag['newRecord'])
                        //     Yii::$app->session->setFlash('success', 'Berjaya menambah rekod sasaran semburan.');
                        // else
                        Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod sasaran semburan.');
                        return $this->redirect(['sasaran', 'nosiri' => $model->NOSIRI]);
                    }
                }
            }
        }

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
                $sasaran = new SasaranSrt();           
                return $this->renderAjax('extra/_sasaran-form', [
                    'sasaran' => $sasaran,
                    'counter' => $counter,
                ]);
            } else {
                $sasaran = SasaranSrt::findOne(['NOSIRI' => $initialize]);
                $sasaran = $sasaran->premis;
                // var_dump($sasaran);
                // exit();

                if ($sasaran) {
                    $htmlOutput = [];
                    foreach ($sasaran as $key => $item) {
                        $data = [];
                        $sasaran = new SasaranSrt();                        
                        $data['ID_JENISPREMIS'] = $item->ID_JENISPREMIS;
                        $data['JUMPREMIS'] = $item->JUMPREMIS;
                        $data['PENCAPAIAN1'] = $item->PENCAPAIAN1;
                        $data['PENCAPAIAN2'] = $item->PENCAPAIAN2;

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
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    // $actionHandler->gotoReturnUrl($model); //return to index page -NOR07102022
                }
                // print_r($model->errors);
                // exit();
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
    public function actionRacun($nosiri, $idracun= null)
    {
        $model = $this->findModel($nosiri);
        /* for auditlog */
        $log = new LogActions;
        $tindakan = $log::ACTION_CREATE;
        $oldmodel = null;
        /* */        

        $searchModel = new RacunSrtSearch();          
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->NOSIRI);

        $flag['newRecord'] = true;
        $racun = new RacunSrt();
        // var_dump($racun);
        // exit();
        $racun->NOSIRI = $model->NOSIRI;

        if ($idracun) {
            $racun = RacunSrt::findOne(['ID' => $idracun]);
            $flag['newRecord'] = false;

            $racun->ID = $idracun;

            /* for auditlog */
            $tindakan = $log::ACTION_UPDATE;
            $oldmodel = json_encode($racun->getAttributes());
            /* */            
        }

        // $count = RacunSrt::find()->count();
        // $racun->ID = ($count + 1);
        
        if ($racun->load(Yii::$app->request->post()) && $racun) {
                        
            if($racun->isNewRecord){
                $racun->ID = Yii::$app->db->createCommand("UPDATE TBRACUN_SRT SET ID = ROWNUM")->execute();     

                $count = RacunSrt::find()->count();
                // $count = RacunSrt::find()->max('ID'); //get last ID
                $racun->ID = ($count + 1);    
            }

            if ($racun->save()) {
                // record log
                 $log->recordLog($tindakan, $racun, $oldmodel);

                if ($flag['newRecord'])
                    Yii::$app->session->setFlash('success', 'Berjaya menambah rekod Penggunaan Racun.');
                else
                    Yii::$app->session->setFlash('success', 'Berjaya mengemaskini rekod Penggunaan Racun.');
                return $this->redirect(['racun', 'nosiri' => $model->NOSIRI]);
            }
            // print_r($racun->errors);
            // exit();
        }

        return $this->render('extra/racun', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'racun' => $racun,
        ]);
    }

    public function actionRacunView($nosiri, $ID)
    {
        $model = RacunSrt::findOne(['ID' => $ID]);

        return $this->render('extra/racun-view', [
            'model' => $model,
        ]);
    }

    public function actionRacunDelete()
    {
        if ($post = Yii::$app->request->post()) {
            $racun = RacunSrt::findOne(['NOSIRI' => $post['nosiri'], 'ID' => $post['idracun']]);
            if ($racun) {
                $racun->delete();
                
                // record log
                $log = new LogActions;
                $log->recordLog($log::ACTION_DELETE, $racun);

                Yii::$app->session->setFlash('success', 'Berjaya menghapuskan rekod Penggunaan Racun ' . $racun->ID);
                return $this->redirect(['racun', 'nosiri' => $racun->NOSIRI]);
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
            'idModule' => 'SRT',
            'title' => 'Semburan Termal (SRT)',
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
