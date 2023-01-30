<?php

namespace backend\modules\lookup\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\utilities\ActionHandler;
use backend\modules\vektor\models\PemeriksaanTandas;
use backend\modules\integrasi\models\Sewa;
use backend\modules\integrasi\models\SewaSearch;

/**
 * Parameter controller
 */
class SewaController extends Controller
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
                        'actions' => ['sewa','sewa-text','view'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * Finds the Lesen Sewa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Lesen Sewa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sewa::findOne([$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Display at popup lookup
     */
    public function actionSewa()
    {

        $searchModel = new SewaSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       // $searchModel->status = 1;
    //    var_dump($dataProvider);
    //     exit();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams, null, $ACCOUNT_NUMBER, $excludeUsed);

        $this->layout = 'main';
        return $this->render('esewa', [//file view dalam folder lookup
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

        return $this->render('@backend/modules/integrasi/views/sewa/view', [
            'model' => $model,
        ]);
    }

    /**
     * After click button 'Pilih', the output will display at textbox
     */
    public function actionSewaText($page = 1, $nosewa = null, $allModel = false) //$nosewa is object that declare at js script
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        if (!empty($nosewa)) {
            $query = Sewa::find()->where(['ACCOUNT_NUMBER' => $nosewa]);


            if ($allModel == true) {
                $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();

                foreach ($results as $result) {
                    $output['results'][] = ['ACCOUNT_NUMBER' => $result->ACCOUNT_NUMBER];
                }
            } else {
                $results = $query->one();

                if (!empty($results)) {
                    $output['results'] = [
                        'ACCOUNT_NUMBER' => $results->ACCOUNT_NUMBER,
                        'LICENSE_NUMBER' => $results->LICENSE_NUMBER,
                        'NAME'=> $results->NAME,
                        'ADDRESS_1' => $results->ADDRESS_1,
                        'ADDRESS_2' => $results->ADDRESS_2,
                        'ADDRESS_3' => $results->ADDRESS_3,
                        'ADDRESS_POSTCODE' => $results->ADDRESS_POSTCODE,
                        'LOT_NO' => $results->LOT_NO,
                        'LOCATION_ID' => $results->LOCATION_ID,
                        'LOCATION_NAME' => $results->LOCATION_NAME,
                        'RENT_CATEGORY' => $results->RENT_CATEGORY,
                        'SALES_TYPE' => $results->SALES_TYPE,
                        'ASSET_ADDRESS_1' => $results->ASSET_ADDRESS_1,
                        'ASSET_ADDRESS_2' => $results->ASSET_ADDRESS_2,
                        'ASSET_ADDRESS_3' => $results->ASSET_ADDRESS_3,
                        'ASSET_ADDRESS_POSTCODE' => $results->ASSET_ADDRESS_POSTCODE,
                        'ASSET_ADDRESS_LAT' => $results->ASSET_ADDRESS_LAT,
                        'ASSET_ADDRESS_LONG' => $results->ASSET_ADDRESS_LONG,
                        'RENT_AMOUNT' => $results->RENT_AMOUNT,
                        'OUTSTANDING_RENT_AMOUNT' => $results->OUTSTANDING_RENT_AMOUNT,
                    ];
                 }
            }
            return $output;
        }    
    }

}