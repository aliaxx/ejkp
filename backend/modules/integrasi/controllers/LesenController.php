<?php

namespace backend\modules\integrasi\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;

use backend\components\LogActions;
use backend\modules\integrasi\models\Penjaja;
use backend\modules\integrasi\models\LesenMaster;
use backend\modules\integrasi\models\LesenSearch;
use yii\web\Response;

/**
 * KategoriController implements the CRUD actions for Perkara model.
 */
class LesenController extends Controller
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
                        'allow' => Yii::$app->access->can('lesen-read'),
                        'actions' => ['index', 'view', ],
                        'roles' => ['@'],
                    ],
                    // [
                    //     'allow' => Yii::$app->access->can('perkara-write'),
                    //     'actions' => ['create', 'update', 'delete'],
                    //     'roles' => ['@'],
                    // ],
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
     * Lists all LesenMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LesenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-csv' => function ($model) use ($dataProvider) {
                    $rows = [];
                    $header = [
                        $model->getAttributeLabel('NO_AKAUN'),
                        $model->getAttributeLabel('ID_PERMOHONAN'),
                        $model->getAttributeLabel('NO_KP_PEMOHON'),
                        $model->getAttributeLabel('NAMA_PEMOHON'),
                        $model->getAttributeLabel('NAMA_SYARIKAT'),
                        $model->getAttributeLabel('NO_DFT_SYKT'),
                        $model->getAttributeLabel('TARIKH_PERMOHONAN'),
                        $model->getAttributeLabel('ALAMAT_PREMIS1'),
                        $model->getAttributeLabel('ALAMAT_PREMIS2'),
                        $model->getAttributeLabel('ALAMAT_PREMIS3'),
                        $model->getAttributeLabel('POSKOD'),
                        $model->getAttributeLabel('STATUS_PERMOHONAN'),
                        $model->getAttributeLabel('TARIKH_BATAL_TANGGUH'),
                        $model->getAttributeLabel('KUMPULAN_LESEN'),
                        $model->getAttributeLabel('KETERANGAN_KUMPULAN'),
                        $model->getAttributeLabel('KATEGORI_LESEN'),
                        $model->getAttributeLabel('KETERANGAN_KATEGORI'),
                        $model->getAttributeLabel('JENIS_LESEN'),
                        $model->getAttributeLabel('AMAUN_LESEN'),
                        $model->getAttributeLabel('LOKASI_MENJAJA'),
                        $model->getAttributeLabel('JENIS_JUALAN'),
                        $model->getAttributeLabel('KAWASAN'),
                        $model->getAttributeLabel('ID_KAWASAN'),
                        $model->getAttributeLabel('JENIS_JAJAAN'),
                        $model->getAttributeLabel('NAMA_PEMOHON'),
                    ];
                    $rows[] = $header;
                  
                    $dataProvider->setPagination(false);
                    $models = $dataProvider->getModels();
                    foreach ($models as $model) {
                        $rows[] = [
                            $model->NO_AKAUN, $model->ID_PERMOHONAN, $model->NO_KP_PEMOHON,$model->NAMA_PEMOHON, $model->NAMA_SYARIKAT, $model->NO_DFT_SYKT, 
                            $model->TARIKH_PERMOHONAN, $model->ALAMAT_PREMIS1, $model->ALAMAT_PREMIS2, $model->ALAMAT_PREMIS3,$model->POSKOD, $model->KETERANGAN_KATEGORI,
                            $model->KATEGORI_LESEN, $model->STATUS_PERMOHONAN, $model->TARIKH_BATAL_TANGGUH, $model->KUMPULAN_LESEN, $model->KETERANGAN_KUMPULAN,
                            $model->penjaja->JENIS_LESEN,$model->penjaja->AMAUN_LESEN, $model->penjaja->LOKASI_MENJAJA, $model->penjaja->JENIS_JUALAN, 
                            $model->penjaja->KAWASAN, $model->penjaja->ID_KAWASAN, $model->penjaja->JENIS_JAJAAN, $model->penjaja->NAMA_PEMOHON,
                        ];
                    }

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename='.Yii::$app->controller->id.' Integrasi Lesen.csv');
                    $fp = fopen('php://output', 'w');
                    foreach ($rows as $row) {
                        fputcsv($fp, $row);
                    }
                    fclose($fp);
                    exit();
                },
                'export-pdf' => function ($model) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('_print', ['dataProvider' => $dataProvider]));
                    $mpdf->Output(Yii::$app->controller->id.' Integrasi Lesen.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                },
            ]);
            $actionHandler->execute();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // //called action where the PK are not exist.
    // public function createUrl($id)
    // {
    //     if ($this->urlCreator instanceof Closure) {
    //         return call_user_func($this->urlCreator, $model, $action);
    //     } else {
        
    //      if($this->primaryKey)
    //         {
    //             $key = $this->primaryKey;
    //             $params = [$key=>$model->$key];
    //         }else{
    //             $params = $model->getPrimaryKey(true);
    //              $key = key($params);
    //         }
    //         if (count($params) === 1) {
    //             $params = [$key => reset($params)];
    //         }
    //         return Yii::$app->controller->createUrl($action, $params);
    //     }
    // }

    /**
     * Displays a single LesenMaster model.
     * @param string $id
     * @return mixed
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


        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the LesenMaster model based on its primary key value.
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

}
