<?php

namespace backend\modules\option\controllers;

//use backend\modules\inventori\models\AsetKategori;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\widgets\DependentDropdown;

use common\models\Pengguna;
use common\models\User;
use common\utilities\OptionHandler;
use backend\modules\penyelenggaraan\models\ParamHeader;
use backend\modules\penyelenggaraan\models\ParamDetail;
use backend\modules\penyelenggaraan\models\Perkara;
use backend\modules\penyelenggaraan\models\PerkaraKomponen;
use backend\modules\penyelenggaraan\models\PerkaraKomponenPrgn;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\integrasi\models\Sewa;



/**
 * Parameter controller
 */
class PenyelenggaraanController extends Controller
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
                        'actions' => ['param-header', 'detail', 'kod-perkara', 'kod-komponen', 'akta','pengguna', 'list', 'lokasi-am', 'pemiliklesen', 'list-petak'], //zihan
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }


    /**
     * Return ParamDetail list
     */
    public function actionParamHeader($search = null, $page = 1)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $perPage = 20;
        $output['results'] = [];


        $query = ParamHeader::find()->where(['STATUS' => 1])->orderBy(['KODJENIS' => SORT_ASC]);

        if (!is_null($search)) {
            $query->andWhere(['or', ['like', 'KODJENIS', $search], ['like', 'PRGN', $search]]);
        }

        $output['total'] = $query->count('*');
        $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();
        foreach ($results as $result) {
            $output['results'][] = ['id' => $result->KODJENIS, 'text' => $result->KODJENIS . ' - ' . $result->PRGN];
        }

        //var_dump($output);
        return $output;
    }  

    /**
     * Return the list of : mukim, kodunit
     */
    //this function is used to get koddetail dropdown from tbparameterdetail. only need to define kodjenis in form --NOR180822
    public function actionDetail($search = null, $page = 1, $KODJENIS = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $perPage = 20;
        $output['results'] = [];

        $query = ParamDetail::find()->where(['STATUS' => 1])->orderBy(['PRGN' => SORT_ASC]);


        if ($KODJENIS) {
            $query->andWhere(['KODJENIS' => $KODJENIS]);
        }

        if (!is_null($search)) {
            $query->andWhere(['or', ['like', 'PRGN', $search]]);
        }
        
        $output['total'] = $query->count('*');
        $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();

        // var_dump($results);
        // exit();

        foreach ($results as $result) {
            // text
            $text = $result->PRGN;

            $output['results'][] = ['id' => $result->KODDETAIL, 'text' => $text];
        }

        return $output;
    }

    /**
     * Kodperkara will display depends on jenis list.
     */
    public function actionKodPerkara()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $output = [];
        if ($parents = Yii::$app->request->post('depdrop_parents')) {
            if ($parents) {

                $JENIS = $parents[0];

                $query = Perkara::find()->where(['JENIS' => $JENIS])->orderBy(['KODPERKARA' => SORT_ASC]);
                $results = $query->all();
                foreach ($results as $result) {
                    $output[] = ['id' => $result->KODPERKARA, 'name' => $result->KODPERKARA . ' - ' . $result->PRGN];
                }
                return ['output' => $output];
            }
        }
    }

    /**
     * Kodkomponen will display depends on jenis & kodperkara list.
     */
    public function actionKodKomponen()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $output = [];
        if ($parents = Yii::$app->request->post('depdrop_parents')) {
            if ($parents) {

                $JENIS = $parents[0];
                $KODPERKARA = $parents[1];

                $query = PerkaraKomponen::find()->where(['KODPERKARA' => $KODPERKARA])->orderBy(['JENIS' => SORT_ASC]);
                $results = $query->all();
                foreach ($results as $result) {
                    $output[] = ['id' => $result->KODKOMPONEN, 'name' => $result->KODKOMPONEN . ' - ' . $result->PRGN];

                }
                return ['output' => $output];
            }
        }
    }

    /**
     * Return Kodakta list
     */
    public function actionAkta($search = null, $page = 1)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $perPage = 20;
        $output['results'] = [];

        $query = Akta::find()->where(['STATUS' => 1])->orderBy(['KODAKTA' => SORT_ASC]);

        if (!is_null($search)) {
            $query->andWhere(['or', ['like', 'KODAKTA', $search], ['like', 'PRGN', $search]]);
        }

        $output['total'] = $query->count('*');
        $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();
        foreach ($results as $result) {
            $output['results'][] = ['id' => $result->KODAKTA, 'text' => $result->KODAKTA . ' - ' . $result->PRGN];
        }

        return $output;
    }  

    
    public function actionPengguna($search = null, $page = 1)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $perPage = 20;
        $output['results'] = [];

        $query = Pengguna::find()->where(['STATUS' => 1])->orderBy(['ID' => SORT_ASC]);

        if (!is_null($search)) {
            $query->andWhere(['or', ['like', 'NAMA', $search]]);
        }

        $output['total'] = $query->count('*');
        $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();
        foreach ($results as $result) {
            $output['results'][] = ['id' => $result->ID, 'text' => $result->NAMA];
        }

        return $output;
    }

    public function actionList()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $source = Pengguna::find()->select(['ID', 'NAMA','STATUS']);

            $source = $source->orderBy(['NAMA' => SORT_ASC])->all();

            if ($source) {
                $data = ArrayHelper::map($source, 'ID', 'NAMA');

                return [
                    'success' => true,
                    'results' => $data,
                ];
            }

            return ['success' => false];
        }
        return null;
    }

    
    public function actionLokasiAm()
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $perPage = 20;
        $output['results'] = [];

        $sources = Yii::$app->db->createCommand("SELECT NOMBOR_ZON, NAMA FROM C##AHLIMAJLIS.V_ZON_AM")->queryAll();

        foreach ($sources as $source) {
            $output['results'] []= ['id' => $source['NOMBOR_ZON'].'-'.$source['NAMA'], 'text' => $source['NAMA']];
        }
        return $output;
    }


    public function actionPemiliklesen($page = 1, $NOLESEN = null, $allModel = false) //$nolesen is object that declare at js script
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!empty($NOLESEN)) {
            $query = LawatanPemilik::find()->where(['NOLESEN' => $NOLESEN]);

            if ($allModel == true) {
                $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();

                foreach ($results as $result) {
                    $output['results'][] = ['NOLESEN' => $result->NOLESEN];
                }
            } else {
                $results = $query->one();

                if (!empty($results)) {
                    
                    $output['results'] = [
                        'NOLESEN'=> $results->NOLESEN,
                        'IDMODULE'=> $results->IDMODULE,
                        'NOSSM'=> $results->NOSSM,
                        'KATEGORI_LESEN'=> $results->KATEGORI_LESEN,
                        'JENIS_JUALAN' => $results->JENIS_JUALAN,
                        'NAMAPEMOHON' => $results->NAMAPEMOHON,
                        'NOKPPEMOHON' => $results->NOKPPEMOHON,
                        'NAMASYARIKAT' => $results->NAMASYARIKAT,
                        'NAMAPREMIS' => $results->NAMAPREMIS,
                        'ALAMAT1' => $results->ALAMAT1,
                        'ALAMAT2' => $results->ALAMAT2,
                        'ALAMAT3' => $results->ALAMAT3,
                        'POSKOD' => $results->POSKOD,
                        'JENIS_PREMIS' => $results->JENIS_PREMIS,
                        'KETERANGAN_KATEGORI'=> $results->KETERANGAN_KATEGORI,
                        'KUMPULAN_LESEN'=> $results->KUMPULAN_LESEN,
                        'KETERANGAN_KUMPULAN'=> $results->KETERANGAN_KUMPULAN,
                        'NOTEL' => $results->NOTEL,
                    ];
                    // var_dump($output['results'] );
                    // exit();
                 }
            }
            return $output;
        }    
    }

    // return No Gerai for Kawalan Perniagaan tab Gerai
    public function actionListPetak($search = null, $page = 1, $locationid = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $perPage = 20;
        $output['results'] = [];

        $query = Sewa::find()->orderBy(['LOT_NO' => SORT_ASC]);

        if ($locationid) {
            $query->andWhere(['LOCATION_ID' => $locationid]);
        }

        if (!is_null($search)) {
            $query->andWhere(['or', ['like', 'LOT_NO', $search]]);
        }
        
        $output['total'] = $query->count('*');
        $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();

        foreach ($results as $result) {
            // text
            $text = $result->LOT_NO;

            $output['results'][] = ['id' => $result->LOT_NO, 'text' => $text];
        }

        return $output;
    }
 

}

   

    

    
