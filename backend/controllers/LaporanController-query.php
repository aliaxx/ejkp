<?php

namespace backend\controllers;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\db\ActiveQuery;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use common\utilities\ActionHandler;
use common\utilities\DateTimeHelper;

use common\models\Pengguna;
use backend\models\LaporanSearch;
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

    public function actionKawalanPerniagaan()
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
                                'url' => './get-kawalan-perniagaan', //get function at LaporanController
                                'searchModel' => $searchModel,
                                'type' => '2'
                            ]); 
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

     public function actionGetKawalanPerniagaan($jenislaporan, $TRKHMULA, $TRKHTAMAT,$IDLOKASI)
    {
        //declare variable for query
        $sqlstmt = '';
        $sqlstmt1 = '';
        $sqlstmt2 = '';

        $title = "Sistem Jabatan Perkhidmatan dan Persekitaran (eJKP)";
        $subTitle = "Majlis Bandaraya Petaling Jaya" . "\n" . "(MBPJ)";  

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        //Laporan terperinci
        if ($jenislaporan == 1){ 
            // var_dump('jenis1');
            //    exit;

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

        }else if($jenislaporan == 2) {

            $rpttitle= ' Laporan Penggerai Ada Berniaga / Tidak Berniaga Langsung / Sendiri'. "\n" .' PPKP/ PPKPK';

                      // $query = Transgerai::find()
            //     ->select(['TRKHLAWATAN_GERAI', 'STATUSPEMANTAUAN_PRGN' => new \yii\db\Expression(
            //                     "CASE
            //                         WHEN STATUSPEMANTAUAN = 1 THEN 'AB'
            //                         WHEN STATUSPEMANTAUAN = 2 THEN 'TBS'
            //                         WHEN STATUSPEMANTAUAN = 3 THEN 'TBL'
            //                     END"),
            //               'TBLAWATAN_PEMILIK.JENIS_JUALAN','TBLAWATAN_PEMILIK.NOSEWA','TBLAWATAN_PEMILIK.NOPETAK',
            //               'TBLAWATAN_PEMILIK.NAMAPEMOHON', 'TBLAWATAN_PEMILIK.NOKPPEMOHON',
            //               'V_SEWA_EJKP.LOCATION_NAME','TBLAWATAN_MAIN.NOSIRI', 'TBPENGGUNA.NAMA'
            //             ])
            //     ->innerJoin(['TBLAWATAN_MAIN'],'"TBTRANS_GERAI"."NOSIRI" = "TBLAWATAN_MAIN"."NOSIRI"')
            //     ->innerJoin(['TBLAWATAN_PEMILIK'],'"TBTRANS_GERAI"."NOSIRI" = "TBLAWATAN_PEMILIK"."NOSIRI" AND "TBTRANS_GERAI"."IDPEMILIK"="TBLAWATAN_PEMILIK"."ID" ' )
            //     ->innerJoin(['C##ESEWA.V_SEWA_EJKP'],'"TBLAWATAN_MAIN"."IDLOKASI" = "V_SEWA_EJKP"."LOCATION_ID" AND "TBLAWATAN_PEMILIK"."NOPETAK" = "V_SEWA_EJKP"."LOT_NO"')
            //     ->innerJoin(['TBPENGGUNA'],'"TBLAWATAN_MAIN"."PGNDAFTAR" = "TBPENGGUNA"."ID"')
            //     ->andWhere(['TBLAWATAN_MAIN.IDLOKASI' => $IDLOKASI])
            //     ->andWhere(['TBLAWATAN_PEMILIK.NOPETAK' => $NOPETAK])
            //     ->andWhere(["TO_CHAR(TRUNC(TRKHLAWATAN_GERAI,'MM'),'MM')"=> '11'])
            //     ->andWhere(['in','STATUSPEMANTAUAN', [1,2,3]]);
            //     //->all();

            $sqlstmt = "SELECT  B.NOSIRI, to_char(A.TRKHLAWATAN_GERAI,'MonthYYYY') AS BULAN, C.NOPETAK AS GERAI, C. NAMAPEMOHON AS PEMOHON,  
                        D.LOCATION_NAME AS LOKASI, C.JENIS_JUALAN AS JUALAN, C.JENISSEWA,
                        CASE 
                        WHEN A.STATUSPEMANTAUAN=0 THEN 'Kosong'
                        WHEN A.STATUSPEMANTAUAN=1 THEN 'Ada Berniaga'
                        WHEN A.STATUSPEMANTAUAN=2 THEN 'Tidak Berniaga Sendiri'
                        WHEN A.STATUSPEMANTAUAN=3 THEN 'Tidak Berniaga Langsung'
                        WHEN A.STATUSPEMANTAUAN=4 THEN 'Ada Berniaga'
                        END AS STATUSPEMANTAUAN
                        FROM TBTRANS_GERAI A
                        LEFT JOIN TBLAWATAN_MAIN B ON A.NOSIRI=B.NOSIRI
                        LEFT JOIN TBLAWATAN_PEMILIK C ON A.NOSIRI=C.NOSIRI AND A.IDPEMILIK=C.ID
                        LEFT JOIN C##ESEWA.V_LOCATION_LIST D ON B.IDLOKASI=D.LOCATION_ID
                        LEFT JOIN TBPENGGUNA E ON B.PGNDAFTAR=E.ID
                        WHERE B.STATUS=1 AND B.IDMODULE='KPN'
                        and B.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and B.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')";

            $sqlstmt2= "ORDER BY to_char(A.TRKHLAWATAN_GERAI, 'MON'),C.NOPETAK"; 
 
            $landscape = true;
            $perPage =38;
        }
        // } else if($jenislaporan == 3) {

        //     // return $this->render('laporan/calendar-gerai'); 
        //    return Url::toRoute(['/laporan/calendar-gerai']);

        // }
        
        if ($IDLOKASI <> ''){
            $sqlstmt1 = " and B.IDLOKASI = $IDLOKASI";
        }else {
            $sqlstmt1 ="";
        }

        $source = Yii::$app->db->createCommand($sqlstmt.$sqlstmt1.$sqlstmt2)->queryAll();
        
        $source = json_decode(json_encode($source, JSON_NUMERIC_CHECK), true);  // Fix all Numerics
        $callback = function($row) {
            if (array_key_exists('TAHUN', $row)) {
                $row['TAHUN'] = (string)$row['TAHUN'];  // Fix strings
            }
            return $row;
        };
        
        $source = array_map($callback, $source);    //final encode

        //output in Laporan format/design
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

    public function actionGetPegawai()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $source = Yii::$app->db->createCommand("SELECT A.PGNDAFTAR AS PEGAWAI, B.NAMA AS NAMA
            from TBLAWATAN_MAIN A, TBPENGGUNA B where A.PGNDAFTAR=B.ID")->queryAll();

            
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

    
   public function actionCalendarGerai($jenislaporan, $TRKHMULA, $TRKHTAMAT, $nopetak)
   //public function actionCalendarGerai()
   {
       //$month = date('m', strtotime($TRKHMULA));

       $query = Transgerai::find()
                   ->select(['TRKHLAWATAN_GERAI', 'STATUSPEMANTAUAN_PRGN' => new \yii\db\Expression("CASE
                               WHEN STATUSPEMANTAUAN = 1 THEN 'AB'
                               WHEN STATUSPEMANTAUAN = 2 THEN 'TBS'
                               WHEN STATUSPEMANTAUAN = 3 THEN 'TBL'
                               END"), '{{%LAWATAN_PEMILIK}}.NAMAPEMOHON' ])
                   ->joinWith(['kpn0', 'idPemilik0'])
                   ->where(['{{%LAWATAN_PEMILIK}}.NOPETAK'=> '24'])
                   ->andWhere(["TO_CHAR(TRUNC(TRKHLAWATAN_GERAI,'MM'),'MM')"=> 11])
                   ->andWhere(['in','STATUSPEMANTAUAN', [1,2,3]]);


       $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 30],
            'sort'=> ['defaultOrder' => ['start'=>SORT_DESC]]
        ]);


       return $this->render('@backend/modules/peniaga/views/kawalan-perniagaan/_calendargerai', [
           //'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
       ]);
    }

}
