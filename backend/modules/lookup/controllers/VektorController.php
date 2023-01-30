<?php

namespace backend\modules\lookup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\utilities\ActionHandler;
use backend\modules\vektor\models\PemeriksaanTandas;
use backend\modules\integrasi\models\LesenMaster;
use backend\modules\integrasi\models\LesenSearch;

/**
 * Parameter controller
 */
class VektorController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['lesen','lesen-text','view'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }


    /**
     * Finds the Lesen Master model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return LesenMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LesenMaster::findOne([$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Display at popup lookup
     */
    public function actionLesen()
    {
        
        $searchModel = new LesenSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       // $searchModel->status = 1;
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams, null, $NO_AKAUN, $excludeUsed);

        $this->layout = 'main';
        return $this->render('elesen', [//file view dalam folder lookup
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $actionHandler->execute();
            
            // Refresh model information
            $model = $this->findModel($id);
        }

        return $this->render('@backend/modules/integrasi/views/lesen/view', [
            'model' => $model,
        ]);
    }


    /**
     * After click button 'Pilih', the output will display at textbox
     */
    public function actionLesenText($page = 1, $nolesen = null, $allModel = false) //$nolesen is object that declare at js script
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!empty($nolesen)) {
            $query = LesenMaster::find()->where(['NO_AKAUN' => $nolesen]);

            if ($allModel == true) {
                $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();

                foreach ($results as $result) {
                    $output['results'][] = ['NO_AKAUN' => $result->NO_AKAUN];
                }
            } else {
                $results = $query->one();

                if (!empty($results)) {
                    $output['results'] = [
                        'NO_AKAUN' => $results->NO_AKAUN, 
                        'NO_KP_PEMOHON' => $results->NO_KP_PEMOHON, 
                        'NAMA_PEMOHON' => $results->NAMA_PEMOHON, 
                        'NAMA_SYARIKAT' => $results->NAMA_SYARIKAT,
                        'NO_DFT_SYKT' => $results->NO_DFT_SYKT, 
                        'ALAMAT_PREMIS1' => $results->ALAMAT_PREMIS1, 
                        'ALAMAT_PREMIS2' => $results->ALAMAT_PREMIS2, 
                        'ALAMAT_PREMIS3' => $results->ALAMAT_PREMIS3, 
                        'POSKOD' => $results->POSKOD, 
                        'JENIS_JUALAN' => $results->penjaja->JENIS_JUALAN, 
                        'JENIS_PREMIS' => $results->JENIS_PREMIS, 
                        'KETERANGAN_KATEGORI' => $results->KETERANGAN_KATEGORI,
                    ];
                 }
            }
            return $output;
        }    
    }

}