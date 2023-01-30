<?php

namespace backend\controllers;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\Response;
use yii\db\ActiveQuery;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use Mpdf\Mpdf;
use kartik\mpdf\Pdf;
use Spipu\Html2Pdf\Html2Pdf;
use Mpdf\Output\Destination;

use common\utilities\ActionHandler;
use common\utilities\DateTimeHelper;

use common\models\Pengguna;
use backend\models\CalendarSearch;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\models\LaporanSearch;
use backend\modules\integrasi\models\Sewa;
use backend\modules\peniaga\models\Transgerai;
use backend\modules\peniaga\models\TransgeraiSearch;
use backend\modules\peniaga\models\CalendarGeraiSearch;

class LaporanController extends Controller
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
                      //  'actions' => ['kawalan-perniagaan', 'get-kawalan-perniagaan'],
                        'roles' => ['@'],
                        
                    ],
                ],
            ],
        ];
    }

     /**
     * Laporan Kawalan Perniagaan.
     * The action button from Kawalan Perniagaan Laporan will go here .
     * The page will redirect to Index Page.
     */
    public function actionKawalanPerniagaan()
    {
        $searchModel = new LaporanSearch();

        if (Yii::$app->request->post('action')) {
            $dataProvider = $searchModel->search(Yii::$app->request->post());

            if ($searchModel->validate()) { 
                $actionHandler = new ActionHandler($searchModel, [
                    
                    //'laporan' is action button id from file _filter.php
                    'laporan' => function ($model) use ($searchModel, $dataProvider) {
 
                        if($model->jenislaporan == 1){

                            return $this->render('rpt/index', [
                                'url' => './get-laporan-peniaga', //get function at LaporanController
                                'searchModel' => $searchModel,
                                'type' => '2'
                            ]);
                        }
                        
                        else{ //jenis laporan peggerai
                            //read action from GetLaporanPeniaga controller and defined the parameter.
                            return $this->redirect(['./laporan/get-laporan-peniaga', 
                            'jenislaporan' => $model->jenislaporan, 'TRKHMULA' => $model->TRKHMULA, 
                            'TRKHTAMAT' => $model->TRKHTAMAT, 'IDLOKASI' => $model->IDLOKASI, 'NOPETAK' => $model->NOPETAK]);
                        } 
                    },
                ]); 

                $actionHandler->execute();
            }
        }

        return $this->render('kawalanperniagaan/index', [
            'title' => 'Peniaga Kecil & Penjaja',
            'breadCrumbs' => ['Laporan', 'Peniaga Kecil & Penjaja'],
            'searchModel' => $searchModel,
        ]);
    }


     /**
     * Laporan Mutu Makanan.
     * The action button from Laporan Mutu Makanan will go here .
     * The page will redirect to Index Page.
     */
    public function actionMutuMakanan()
    {
        $searchModel = new LaporanSearch();
        $searchModel->jenislaporan = 1;

        ActionHandler::setReturnUrl();
       
        if (Yii::$app->request->post('action')) {
            $dataProvider = $searchModel->search(Yii::$app->request->post());

            if ($searchModel->validate()) { 
                $actionHandler = new ActionHandler($searchModel, [
                    
                    'quickreport' => function ($model) use ($searchModel, $dataProvider) {
                        return $this->render('rpt/index', [
                                'url' => './get-mutu-makanan', //get function at LaporanController
                                'searchModel' => $searchModel,
                                'type' => '2'
                            ]); 
                    },
                ]);
                
                $actionHandler->execute();
            }
        }

        return $this->render('mutumakanan/index', [
            'title' => 'Mutu Makanan',
            'breadCrumbs' => ['Laporan', 'Mutu Makanan'],
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * Action from Laporan Kawalan Perniagaan.
     * Get data from index page and display to file laporan_pegerai.
     * Display data.
     */
    public function actionGetLaporanPeniaga($jenislaporan, $TRKHMULA, $TRKHTAMAT, $IDLOKASI, $NOPETAK)
    {
        
        //Laporan terperinci
        if ($jenislaporan == 1){ 

            //declare variable for query
            $sqlstmt = '';

            $title = "Sistem Jabatan Perkhidmatan dan Persekitaran (eJKP)";
            $subTitle = "Majlis Bandaraya Petaling Jaya" . "\n" . "(MBPJ)";  

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $rpttitle= 'Laporan Terperinci Kawalan Perniagaan Berdasarkan Tarikh Lawatan';

            $sqlstmt = "SELECT A.NOSIRI, A.TRKHMULA AS tarikhlawatan,
                        B.PRGN as tujuanlawatan, A.PRGNLOKASI_AM as lokasiahlimajlis, 
                        NVL(C.NAMAPREMIS, '-') as namapremis, NVL(C.NOLESEN, '-') as nolesen, -- if data is null (Oracle use NVL)
                        --display status(name) aktif instead of 1
                        case
                            when A.STATUS = 1 then 'Aktif'
                            else 'Tidak Aktif'
                        end as status
                        FROM TBLAWATAN_MAIN A 
                        LEFT OUTER JOIN TBPARAMETER_DETAIL B on A.ID_TUJUAN = B.KODDETAIL and B.KODJENIS=22   --id_tujuan merujuk pada kodjenis 22(tujuanlawatan) tetapi koddetail dimasukkan ke A.             
                        LEFT OUTER JOIN TBLAWATAN_PEMILIK C on A.NOSIRI = C.NOSIRI
                        WHERE A.STATUS = '1' and A.IDMODULE = 'KPN'
                        and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')";

            $source = Yii::$app->db->createCommand($sqlstmt)->queryAll();

            $source = json_decode(json_encode($source, JSON_NUMERIC_CHECK), true);  // Fix all Numerics

            $callback = function($row) {
                if (array_key_exists('TAHUN', $row)) {
                    $row['TAHUN'] = (string)$row['TAHUN'];  // Fix strings
                }
                return $row;
            };
            
            $source = array_map($callback, $source);    //final encode
    
            //output in pdf format/design
            $pdf = array(
                "header" =>array("title"=> $title ,"subTitle"=> $subTitle),
                "title"=> $rpttitle,
                // "title1"=> $rpttitle2,
                "subTitle"=> "Dari " . str_replace("/","-",$TRKHMULA) . " Hingga " . str_replace("/","-",$TRKHTAMAT),
                "landscape"=> true, // true for landscape atau false for potrait.
                "color" =>array("header"=> "#ffa75b","footer"=> "#ffa75b"),
                "rows"=> array("perPage"=> 38), //berapa data yang akan display untuk satu page
                "fontSize" => 8,
                "weights" => array(0.1,0.8,1,1,1,1,1,0.5), //width for each column di laporan.
                "printedBy" => Yii::$app->user->identity->NOKP,
                "paginationFormatString" => "Mukasurat {0} dari {1}",
                "align" => array(0=> "center", 1=> "center", 2=> "center", 3=> "center", 4=> "center", 5=> "center", 6=> "center", 7=> "center"), //make text align center for each column
            );     
    
            $config = array("pdf"=> $pdf, "sequenceTitle" => "NO");
            
            return [
                'config' =>  $config,
                'data' => $source,
            ];

        }

        //Laporan Pegerai
        else{
            //get month, so the data will display based on selected month.
            // $month = date('m', strtotime($TRKHMULA));
            $month = date("f",strtotime($TRKHMULA));
 

            //query for display data at Laporan Pegerai EXCEPT Calendar
            $sqlstmt = "SELECT to_char(A.TRKHMULA,'MonthYYYY') AS BULAN,
                    B.NOPETAK, B.NAMAPEMOHON, B.NOKPPEMOHON, B.JENIS_JUALAN, 
                    C.LOCATION_NAME, E.NAMA, F.CATATAN, F.NAMAPEKERJA, F.NOKPPEKERJA, F.HUBUNGAN
                    FROM TBLAWATAN_MAIN A, TBLAWATAN_PEMILIK B, C##ESEWA.V_LOCATION_LIST C, TBLAWATAN_PASUKAN D, TBPENGGUNA E, TBTRANS_GERAI F
                    WHERE
                    A.STATUS=1 AND A.IDMODULE='KPN' AND NOPETAK = $NOPETAK AND IDLOKASI = $IDLOKASI
                    -- AND A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                    -- AND A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')
                    AND A.NOSIRI = B.NOSIRI 
                    AND A.IDLOKASI=C.LOCATION_ID
                    AND A.NOSIRI = D.NOSIRI AND JENISPENGGUNA = 1
                    AND D.IDPENGGUNA = E.ID
                    AND A.NOSIRI = F.NOSIRI AND B.NOSIRI = F.NOSIRI AND B.ID = F.IDPEMILIK";
                    // AND TO_CHAR(TRUNC('A.TRKHMULA','MM'),'MM') = $month";

            $source = Yii::$app->db->createCommand($sqlstmt)->queryOne();

            //query for display data StatusPemantauan at Calendar.
            $query = Transgerai::find()
                   ->select(['TRKHLAWATAN_GERAI', 'STATUSPEMANTAUAN_PRGN' => new \yii\db\Expression("CASE
                               WHEN STATUSPEMANTAUAN = 1 THEN 'AB'
                               WHEN STATUSPEMANTAUAN = 2 THEN 'TBS'
                               WHEN STATUSPEMANTAUAN = 3 THEN 'TBL'
                               END"), '{{%LAWATAN_PEMILIK}}.NAMAPEMOHON' ])
                   ->joinWith(['kpn0', 'idPemilik0'])
                   ->where(['{{%LAWATAN_PEMILIK}}.NOPETAK'=> $NOPETAK])
                   ->andWhere(["TO_CHAR(TRUNC(TRKHLAWATAN_GERAI,'MM'),'MM')"=> '11'])
                   ->andWhere(['in','STATUSPEMANTAUAN', [1,2,3]]);
                
            
            $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => ['pageSize' => 30],
                    'sort'=> ['defaultOrder' => ['start'=>SORT_DESC]]
                ]);

                
            // $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
            // $mpdf->WriteHTML($this->render('@backend/views/laporan/kawalanperniagaan/laporan_pegerai',['dataProvider' => $dataProvider, 'source' => $source]));
            // $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);
            return $this->render('@backend/views/laporan/kawalanperniagaan/laporan_pegerai', [
                'dataProvider' => $dataProvider, 
                'source' => $source,
            ]);
        } 
    }


    /**
     * Laporan Kawalan Perniagaan.
     * Display Nama Pegawai.
     * Data will display based on PGNDAFTAR.
     */
    public function actionGetPegawai()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $source = Yii::$app->db->createCommand("SELECT A.IDPENGGUNA AS PEGAWAI, B.NAMA AS NAMA
        from TBLAWATAN_PASUKAN A, TBPENGGUNA B where A.IDPENGGUNA=B.ID AND JENISPENGGUNA=1")->queryAll();

            if ($source) {
                $data = yii\helpers\ArrayHelper::map($source, 'PEGAWAI', 'NAMA');
                return [
                    'success' => true,
                    'results' => $data,
                ];
            }

            return ['success' => false];

        return null;
    }


    /**
     * Laporan Kawalan Perniagaan.
     * Depdrop action for No.Petak.
     * Depends data on Lokasi Penjaja(IDLOKASI).
     */
    public function actionGerai()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [];

        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $lokasi= $parents[0];
                $gerai = Sewa::find()->andWhere(['LOCATION_ID' => $lokasi])->all();

                foreach ($gerai as $petak) {
                    $out[] = ['id' => $petak->LOT_NO, 'name' => $petak->LOT_NO];
                }

                return ['output' => $out, 'selected'=>''];
            }
        }

        return ['output'=> $out, 'selected'=> ''];
    }


    
   public function actionCalendarGerai()
   //public function actionCalendarGerai()
   {
       $month = date('m', strtotime($TRKHMULA));

       $query = Transgerai::find()
                   ->select(['TRKHLAWATAN_GERAI', 'STATUSPEMANTAUAN_PRGN' => new \yii\db\Expression("CASE
                               WHEN STATUSPEMANTAUAN = 1 THEN 'AB'
                               WHEN STATUSPEMANTAUAN = 2 THEN 'TBS'
                               WHEN STATUSPEMANTAUAN = 3 THEN 'TBL'
                               END"), '{{%LAWATAN_PEMILIK}}.NAMAPEMOHON' ])
                   ->joinWith(['kpn0', 'idPemilik0'])
                   ->where(['{{%LAWATAN_PEMILIK}}.NOPETAK'=> '24'])
                   ->andWhere(["TO_CHAR(TRUNC(TRKHLAWATAN_GERAI,'MM'),'MM')"=>  $month])
                   ->andWhere(['in','STATUSPEMANTAUAN', [1,2,3]]);


       $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 30],
            'sort'=> ['defaultOrder' => ['start'=>SORT_DESC]]
        ]);

       return $this->render('@backend/modules/peniaga/views/kawalan-perniagaan/laporan_pegerai-calendar', [
           //'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
       ]);
    }

}
