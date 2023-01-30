<?php

namespace backend\modules\premis\controllers;

use backend\modules\premis\models\Anugerah;
use backend\modules\premis\models\AnugerahSearch;
use backend\modules\premis\models\LawatanApbSearch;
// use backend\modules\premis\models\PremisSearch;
use backend\modules\lawatanmain\models\LawatanMain;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\utilities\ActionHandler;

/**
 * AnugerahController implements the CRUD actions for Anugerah model.
 */
class AnugerahController extends Controller
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
                        'allow' => Yii::$app->access->can('APB-read'),
                        'actions' => ['index', 'view', 'views'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('APB-write'),
                        'actions' => ['create', 'update', 'delete', 'carian', 'carian1'],
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
        $searchModel = new AnugerahSearch();
        $dataProvider = $searchModel->search('PPM',$this->request->queryParams);
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                
                'export-pdf' => function ($model) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('_print-grid', ['dataProvider' => $dataProvider]));
                    $mpdf->Output(Yii::$app->controller->id . ' Anugerah.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                },
            ]);
            $actionHandler->execute();
        }
        
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }


    /**
     * Views for LawatanMain.
     */
    public function actionViews($id)
    {
        // var_dump("view");
        // exit;
        $model = $this->findModel($id);

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $actionHandler->execute();

            // Refresh model information
            $model = $this->findModel($id);
        }

        // return $this->render('@backend/modules/lawatanmain/views/viewpenggredan', [
        return $this->render('viewpenggredan', [
            'idModule' => 'APB',
            'title' => 'Paparan Premis',
            'breadCrumbs' => ['Penggredan Premis Makanan', 'Anugerah Premis Bersih', $model->NOSIRI, 'Paparan Maklumat'],
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Anugerah model.
     * @param string $id Nosiri
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModels($id);

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $actionHandler->execute();

            // Refresh model information
            $model = $this->findModel($id);
        }

        return $this->render('view', [
            'idModule' => 'APB',
            'title' => 'Anugerah Premis Bersih',
            'titleMain' => 'Penggredan Premis Makanan',
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Anugerah model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Anugerah();

        // $lawatan = $this->findModel($id);
        $lawatan = LawatanMain::find()->where(['NOSIRI' => $id])->one();
        // var_dump($lawatan->pemilik0->NOLESEN);
        // exit;
        if (Yii::$app->request->post('action')) {
            // if($model){

            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {

                
                $model->setNosiri('APB'); //set No Siri.

                $model->NOLESEN = $lawatan->pemilik0->NOLESEN;
                $model->NOSSM = $lawatan->pemilik0->NOSSM;
                $model->PGNDAFTAR = Yii::$app->user->ID;
                $model->TRKHDAFTAR = strtotime(date('Y-m-d H:i:s'));
                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));
                //$model->save(false);

                if ($model->save(false)) {
                // print_r($model->errors);
                // exit;
                    // record log
                    // $log = new LogActions;
                    // $log->recordLog($log::ACTION_CREATE, $model);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }

        // var_dump($id)
        return $this->render('create', [
            'idModule' => 'APB',
            'title' => 'Anugerah Premis Bersih',
            'titleMain' => 'Penggredan Premis Makanan',
            'model' => $model,
            // 'id' => $id,
            'lawatan' => $lawatan,
        ]);
        
    }

    /**
     * Updates an existing Anugerah model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $NOSIRI Nosiri
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $model = $this->findModels($id);

        // var_dump($model);
        // exit();
        
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();
            // var_dump($model->KETUAPASUKAN);
            // exit();
            // old data for logging update action
            $oldmodel = json_encode($model->getAttributes());

            if ($action && $model->load(Yii::$app->request->post())) {
                
                $model->PGNAKHIR = Yii::$app->user->ID;
                $model->TRKHAKHIR = strtotime(date('Y-m-d H:i:s'));
                //$model->save(false);
                if ($model->save(false)) {
                    
                    // // record log
                    // $log = new LogActions;
                    // $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }


        return $this->render('update', [
            'idModule' => 'APB',
            'title' => 'Anugerah Premis Bersih',
            'titleMain' => 'Penggredan Premis Makanan',
            'model' => $model,
        ]);
    }

    public function actionCarian()
    {
        $searchModel = new LawatanApbSearch();

        // $searchModel = new PremisSearch();

        $dataProvider = $searchModel->search('PPM',$this->request->queryParams);

        // $model= new LawatanMain();
            // $dataProvider = $searchModel->search('PPM',$this->request->queryParams);

                // return $this->render('@backend/modules/lawatanmain/views/_carianlawatan', [
                return $this->render('carian', [
                    'idModule' => 'APB',
                    'title' => 'Anugerah Premis Bersih',
                    'titleMain' => 'Penggredan Premis Makanan',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    // 'model' => $model,
                ]);
    }

    /**
     * Finds the Anugerah model based on its primary key value.
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

    protected function findModels($id)
    {
        if (($model = Anugerah::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
