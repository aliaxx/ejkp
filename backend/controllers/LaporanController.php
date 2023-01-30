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
use cinghie\tcpdf\TCPDF;
use Spipu\Html2Pdf\Html2Pdf;
use Mpdf\Output\Destination;
use aschild\PDFCalendarBuilder\CalendarBuilder;

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

    public function actionGetMutuMakanan($jenislaporan, $TRKHMULA, $TRKHTAMAT)
    {
        $sqlstmt = '';

        $title = "Sistem Jabatan Perkhidmatan dan Persekitaran (eJKP)";
        $subTitle = "Majlis Bandaraya Petaling Jaya" . "\n" . "(MBPJ)";  
        $perPage = 38;
        $weights = '';
        $sum = '';  
        $align = '';
        $landscape = false;  

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($jenislaporan == 1){ 

            $rpttitle= 'Laporan Terperinci Mutu Makanan Berdasarkan Tarikh Lawatan';

            $sqlstmt = "SELECT A.NOSIRI, A.TRKHMULA AS tarikhlawatan, D.PRGN as aktiviti, 
                        NVL(A.PRGNLOKASI, '-') as lokasi, NVL(C.NOLESEN, '-') as nolesen, NVL(C.NOSSM, '-') as NoSSM -- if data is null (Oracle use NVL)
                        FROM TBLAWATAN_MAIN A 
                        LEFT OUTER JOIN TBPARAMETER_DETAIL B on A.ID_TUJUAN = B.KODDETAIL and B.KODJENIS=22   --id_tujuan merujuk pada kodjenis 22(tujuanlawatan) tetapi koddetail dimasukkan ke A.             
                        LEFT OUTER JOIN TBLAWATAN_PEMILIK C on A.NOSIRI = C.NOSIRI
                        LEFT OUTER JOIN TBMODULE D on A.IDMODULE = D.ID
                        WHERE A.STATUS = '1' and A.IDMODULE = 'SMM' or A.IDMODULE = 'SDR' or A.IDMODULE = 'HSW' or A.IDMODULE = 'PKK'
                        and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')";

            $perPage = 38;
            $weights = array(0.1,0.8,1,1,1,1,1,0.5);

        }else if ($jenislaporan == 2) {

            $rpttitle= 'Laporan Bulanan Mutu Makanan Berdasarkan Tarikh Lawatan';
            
            $sqlstmt = "SELECT to_char(A.TRKHMULA,'MM/YYYY') AS tahun,
                        to_char(A.TRKHMULA,'Month') AS BULAN, B.PRGN as aktiviti,
                        count(A.IDMODULE) as jumlah
                        FROM TBLAWATAN_MAIN A
                        LEFT OUTER JOIN TBMODULE B on A.IDMODULE = B.ID
                        WHERE A.STATUS = '1' and A.IDMODULE = 'SMM' or A.IDMODULE = 'SDR' or A.IDMODULE = 'HSW' or A.IDMODULE = 'PKK'
                        and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')
                        GROUP BY to_char(A.TRKHMULA,'MM/YYYY'), to_char(A.TRKHMULA,'Month'), B.PRGN
                        ORDER BY B.PRGN , to_char(A.TRKHMULA,'MM/YYYY')";
 
            // $sum = array(
            //     // "column" => array("title"=>"JUMLAH ATR"),
            //     "row" => array("title"=>"JUMLAH"),
            //     "keys"=> array("jumlah"),
            //     "exclude" => false
            // );    
            
            $perPage = $perPage;
            $weights = array(0.1,0.8,1,1,1,1,1,0.5);
            
        }else if($jenislaporan == 3){
            $rpttitle= 'Laporan Terperinci Aktiviti Swab Tangan';
            
            $sqlstmt = " SELECT A.NOSIRI AS NOSIRI, A.IDSAMPEL AS IDSAMPEL, A.NAMAPEKERJA AS NAMAPEKERJA, 
                        CASE 
                            WHEN A.JENIS=1 THEN 'Lelaki'
                            ELSE 'Perempuan'
                        END AS JENIS,
                        NVL(B.NAMAPREMIS, 'NULL') AS NAMAPREMIS
                        FROM TBSAMPEL_HS A, TBLAWATAN_PEMILIK B, TBLAWATAN_MAIN C
                        WHERE A.NOSIRI = B.NOSIRI AND A.NOSIRI = C.NOSIRI
                        and C.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and C.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS') ";

            $perPage = $perPage;
            $weights = array(0.5,1,1,3,1,3);
            $align = array(2,3,4,5=> "left");
            $align = array(0 => "center");
            
        }else if($jenislaporan == 4){
            $rpttitle= 'Laporan Pemeriksaan Pengendali Makanan (Swab Tangan)';

            $sqlstmt = " SELECT
                            to_char(A.TRKHMULA,'DD/MM/YYYY') AS TARIKH,
                            B.NAMAPEMOHON AS PENGUSAHA,
                            NVL(B.NAMAPREMIS, ' ') AS NAMAPREMIS,
                            NVL(A.PRGNLOKASI, ' ') AS LOKASI,
                            NVL(B.KATEGORI_LESEN,' ') AS KATEGORI,                            
                            NVL((SELECT COUNT(C.NOSIRI) FROM TBSAMPEL_HS C WHERE A.NOSIRI = C.NOSIRI GROUP BY C.NOSIRI),0) AS JUMLAH,
                            NVL((SELECT COUNT(C.NOSIRI) FROM TBSAMPEL_HS C WHERE A.NOSIRI = C.NOSIRI AND C.JENIS='1' GROUP BY C.NOSIRI),0) AS LELAKI,
                            NVL((SELECT COUNT(C.NOSIRI) FROM TBSAMPEL_HS C WHERE A.NOSIRI = C.NOSIRI AND C.JENIS='2' GROUP BY C.NOSIRI),0) AS PEREMPUAN,
                            NVL((SELECT COUNT(C.NOSIRI) FROM TBSAMPEL_HS C WHERE A.NOSIRI = C.NOSIRI AND C.JENIS IS NOT NULL GROUP BY C.NOSIRI),0) AS PERALATAN,
                            NVL((SELECT COUNT(C.NOSIRI) FROM TBSAMPEL_HS C WHERE A.NOSIRI = C.NOSIRI AND C.KEPUTUSAN='1' GROUP BY C.NOSIRI),0) AS POSITIF,
                            NVL((SELECT COUNT(C.NOSIRI) FROM TBSAMPEL_HS C WHERE A.NOSIRI = C.NOSIRI AND C.JENIS='1' AND C.KEPUTUSAN='1' GROUP BY C.NOSIRI),0) AS LELAKI,
                            NVL((SELECT COUNT(C.NOSIRI) FROM TBSAMPEL_HS C WHERE A.NOSIRI = C.NOSIRI AND C.JENIS='2' AND C.KEPUTUSAN='1' GROUP BY C.NOSIRI),0) AS PEREMPUAN,
                            NVL((SELECT COUNT(C.NOSIRI) FROM TBSAMPEL_HS C WHERE A.NOSIRI = C.NOSIRI AND C.JENIS IS NOT NULL AND C.KEPUTUSAN='1' GROUP BY C.NOSIRI),0) AS PERALATAN
                        FROM
                            TBLAWATAN_MAIN A, TBLAWATAN_PEMILIK B
                        WHERE
                            A.NOSIRI = B.NOSIRI AND A.STATUS = '1' 
                            AND A.IDMODULE = 'HSW' AND A.TRKHMULA >= TO_DATE('$TRKHMULA','DD/MM/YYYY') 
                            AND A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT','DD/MM/YYYY') ";

            $perPage = $perPage;
            $weights = array(0.5,2,2,2,2,2,2,2,2,2,2,2);
            $landscape = true;
            $align = array(5,6,7,8,9,10,11=> "right");
            $align = array(0 => "center");
        }

        $source = Yii::$app->db->createCommand($sqlstmt)->queryAll();
        $source = json_decode(json_encode($source, JSON_NUMERIC_CHECK), true);  // Fix all Numerics
        $callback = function($row) {
            if (array_key_exists('TAHUN', $row)) {
                $row['TAHUN'] = (string)$row['TAHUN'];  // Fix strings
            }
            return $row;
        };
        
        $source = array_map($callback, $source);    //final encode
       
        $pdf = array(
            "header" =>array("title"=> $title ,"subTitle"=> $subTitle),
            "title"=> $rpttitle,
            "subTitle"=> "Dari " . str_replace("/","-",$TRKHMULA) . " Hingga " . str_replace("/","-",$TRKHTAMAT),
            //"landscape"=> true,
            "landscape"=> $landscape,
            "color" =>array("header"=> "#ffa75b","footer"=> "#ffa75b"),
            "rows"=> array("perPage"=> $perPage),
            "fontSize" => 8,
            "weights" => $weights,
            "printedBy" => Yii::$app->user->identity->NOKP,
            "paginationFormatString" => "Mukasurat {0} dari {1}",
            // "align" => array(0=> "center", 1=> "center", 2=> "center", 3=> "center", 4=> "center", 5=> "center", 6=> "center", 7=> "center"), //make text align center for each column
            "align" => $align,
        );     
        
        $config = array("pdf"=> $pdf, "sequenceTitle" => "NO");

        if ($sum == '') {
            //$config = array("pdf"=> $pdf, "sequenceTitle" => "NO");
        }else{
            $config['sum'] = $sum;
            //$config = array("sum"=>$sum , "pdf"=> $pdf);   
        }
        
        return [
            'config' =>  $config,
            'data' => $source,
        ];
    } 

    /**
     * Laporan Pencegahan Vektor.
     * The action button from Laporan Mutu Makanan will go here .
     * The page will redirect to Index Page.
     */
    public function actionVektor()
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
                                'url' => './get-vektor', //get function at LaporanController
                                'searchModel' => $searchModel,
                                'type' => '2'
                            ]); 
                    },
                ]);
                
                $actionHandler->execute();
            }
        }

        return $this->render('vektor/index', [
            'title' => 'Pencegahan Vektor',
            'breadCrumbs' => ['Laporan', 'Pencegahan Vektor'],
            'searchModel' => $searchModel,
        ]);
    }

    public function actionGetVektor($jenislaporan, $TRKHMULA, $TRKHTAMAT)
    {
        $sqlstmt = '';

        $title = "Sistem Jabatan Perkhidmatan dan Persekitaran (eJKP)";
        $subTitle = "Majlis Bandaraya Petaling Jaya" . "\n" . "(MBPJ)";  
        $perPage = 38;
        $weights = '';
        $sum = '';  
        $landscape = false;  

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($jenislaporan == 1){ 

            $rpttitle= 'Laporan Terperinci Pencegahan Vektor Berdasarkan Tarikh Lawatan';

            $sqlstmt = "SELECT A.NOSIRI, A.TRKHMULA AS tarikhlawatan, D.PRGN as aktiviti, 
                        NVL(A.PRGNLOKASI, '-') as lokasi, NVL(C.NOLESEN, '-') as nolesen, NVL(C.NOSSM, '-') as NoSSM -- if data is null (Oracle use NVL)
                        FROM TBLAWATAN_MAIN A 
                        LEFT OUTER JOIN TBPARAMETER_DETAIL B on A.ID_TUJUAN = B.KODDETAIL and B.KODJENIS=22   --id_tujuan merujuk pada kodjenis 22(tujuanlawatan) tetapi koddetail dimasukkan ke A.             
                        LEFT OUTER JOIN TBLAWATAN_PEMILIK C on A.NOSIRI = C.NOSIRI
                        LEFT OUTER JOIN TBMODULE D on A.IDMODULE = D.ID
                        WHERE A.STATUS = '1' and A.IDMODULE = 'SMM' or A.IDMODULE = 'SDR' or A.IDMODULE = 'HSW' or A.IDMODULE = 'PKK'
                        and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')";

            $perPage = 38;
            $weights = array(0.1,0.8,1,1,1,1,1,0.5);

        }else if ($jenislaporan == 2) {

            $rpttitle= 'Laporan Bulanan Mutu Makanan Berdasarkan Tarikh Lawatan';
            
            $sqlstmt = "SELECT to_char(A.TRKHMULA,'MM/YYYY') AS tahun,
                        to_char(A.TRKHMULA,'Month') AS BULAN, B.PRGN as aktiviti,
                        count(A.IDMODULE) as jumlah
                        FROM TBLAWATAN_MAIN A
                        LEFT OUTER JOIN TBMODULE B on A.IDMODULE = B.ID
                        WHERE A.STATUS = '1' and A.IDMODULE = 'SMM' or A.IDMODULE = 'SDR' or A.IDMODULE = 'HSW' or A.IDMODULE = 'PKK'
                        and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')
                        GROUP BY to_char(A.TRKHMULA,'MM/YYYY'), to_char(A.TRKHMULA,'Month'), B.PRGN
                        ORDER BY B.PRGN , to_char(A.TRKHMULA,'MM/YYYY')";
 
            // $sum = array(
            //     // "column" => array("title"=>"JUMLAH ATR"),
            //     "row" => array("title"=>"JUMLAH"),
            //     "keys"=> array("jumlah"),
            //     "exclude" => false
            // );    
            
            $perPage = $perPage;
            $weights = array(0.1,0.8,1,1,1,1,1,0.5);
        }

        $source = Yii::$app->db->createCommand($sqlstmt)->queryAll();
        $source = json_decode(json_encode($source, JSON_NUMERIC_CHECK), true);  // Fix all Numerics
        $callback = function($row) {
            if (array_key_exists('TAHUN', $row)) {
                $row['TAHUN'] = (string)$row['TAHUN'];  // Fix strings
            }
            return $row;
        };
        
        $source = array_map($callback, $source);    //final encode
       
        $pdf = array(
            "header" =>array("title"=> $title ,"subTitle"=> $subTitle),
            "title"=> $rpttitle,
            "subTitle"=> "Dari " . str_replace("/","-",$TRKHMULA) . " Hingga " . str_replace("/","-",$TRKHTAMAT),
            //"landscape"=> true,
            "landscape"=> $landscape,
            "color" =>array("header"=> "#ffa75b","footer"=> "#ffa75b"),
            "rows"=> array("perPage"=> $perPage),
            "fontSize" => 7,
            "weights" => $weights,
            "printedBy" => Yii::$app->user->identity->NOKP,
            "paginationFormatString" => "Mukasurat {0} dari {1}",
            "align" => array(0=> "center", 1=> "center", 2=> "center", 3=> "center", 4=> "center", 5=> "center", 6=> "center", 7=> "center"), //make text align center for each column
        );     
        
        $config = array("pdf"=> $pdf, "sequenceTitle" => "NO");

        if ($sum == '') {
            //$config = array("pdf"=> $pdf, "sequenceTitle" => "NO");
        }else{
            $config['sum'] = $sum;
            //$config = array("sum"=>$sum , "pdf"=> $pdf);   
        }
        
        return [
            'config' =>  $config,
            'data' => $source,
        ];
    }


    public function actionPremisMakanan()
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
                                'url' => './get-premis-makanan', //get function at LaporanController
                                'searchModel' => $searchModel,
                                'type' => '2'
                            ]); 
                    },
                ]);
                
                $actionHandler->execute();
            }
        }

        return $this->render('premismakanan/index', [
            'title' => 'Premis Makanan',
            'breadCrumbs' => ['Laporan', 'Premis Makanan'],
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
        //Laporan Pegerai
        if ($jenislaporan == 3){
            //get month, so the data will display based on selected month.
            $d = date_parse_from_format("d/m/Y", $TRKHMULA);
            $month = $d["month"];

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

            // query for display data StatusPemantauan at Calendar.
            $query = Transgerai::find()
                   ->select(['TRKHLAWATAN_GERAI', 'STATUSPEMANTAUAN_PRGN' => new \yii\db\Expression("CASE
                               WHEN STATUSPEMANTAUAN = 1 THEN 'AB'
                               WHEN STATUSPEMANTAUAN = 2 THEN 'TBS'
                               WHEN STATUSPEMANTAUAN = 3 THEN 'TBL'
                               END"), '{{%LAWATAN_PEMILIK}}.NAMAPEMOHON' ])
                   ->joinWith(['kpn0', 'idPemilik0'])
                   ->where(['{{%LAWATAN_PEMILIK}}.NOPETAK'=> $NOPETAK])
                   ->andWhere(["TO_CHAR(TRUNC(TRKHLAWATAN_GERAI,'MM'),'MM')"=> $month])
                   ->andWhere(['in','STATUSPEMANTAUAN', [1,2,3]]);
                
            
            $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => ['pageSize' => 30],
                    'sort'=> ['defaultOrder' => ['start'=>SORT_DESC]]
                ]);

                
            // $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);
            // // $mpdf->WriteHTML($this->render('@backend/views/laporan/kawalanperniagaan/calendar_pdf',['dataProvider' => $dataProvider, 'source' => $source]));
            // $mpdf->WriteHTML($this->render('@backend/views/laporan/kawalanperniagaan/calendar_html',['dataProvider' => $dataProvider, 'source' => $source]));
            // $mpdf->Output(Yii::$app->controller->id . '.pdf', \Mpdf\Output\Destination::INLINE);

            // $cal = new CalendarBuilder(1, 2019, "Calendar title", true, 'mm', 'A4');
            // $cal->startPDF();
            // $cal->addEntry($startDate, $endDate, "Entry 1");
            // $cal->buildCalendar();
            // $cal->Output("calendar.pdf", "I");

            return $this->render('@backend/views/laporan/kawalanperniagaan/laporan_pegerai', [
                'dataProvider' => $dataProvider, 
                'source' => $source,
            ]);
            // return $this->render('@backend/views/laporan/kawalanperniagaan/calendar1', [
            //         'dataProvider' => $dataProvider, 
            //         'source' => $source,
            //     ]);
        } 

        $sqlstmt = '';

        $title = "Sistem Jabatan Perkhidmatan dan Persekitaran (eJKP)";
        $subTitle = "Majlis Bandaraya Petaling Jaya" . "\n" . "(MBPJ)";  
        $perPage = 38;
        $weights = '';
        $sum = '';  
        $landscape = false;  

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($jenislaporan == 1){ 

            $rpttitle= 'Laporan Terperinci Mutu Makanan Berdasarkan Tarikh Lawatan';

            $sqlstmt = "SELECT A.NOSIRI, A.TRKHMULA AS tarikhlawatan, D.PRGN as aktiviti, 
                        NVL(A.PRGNLOKASI, '-') as lokasi, NVL(C.NOLESEN, '-') as nolesen, NVL(C.NOSSM, '-') as NoSSM -- if data is null (Oracle use NVL)
                        FROM TBLAWATAN_MAIN A 
                        LEFT OUTER JOIN TBPARAMETER_DETAIL B on A.ID_TUJUAN = B.KODDETAIL and B.KODJENIS=22   --id_tujuan merujuk pada kodjenis 22(tujuanlawatan) tetapi koddetail dimasukkan ke A.             
                        LEFT OUTER JOIN TBLAWATAN_PEMILIK C on A.NOSIRI = C.NOSIRI
                        LEFT OUTER JOIN TBMODULE D on A.IDMODULE = D.ID
                        WHERE A.STATUS = '1' and A.IDMODULE = 'SMM' or A.IDMODULE = 'SDR' or A.IDMODULE = 'HSW' or A.IDMODULE = 'PKK'
                        and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')";

            $perPage = 38;
            $weights = array(0.1,0.8,1,1,1,1,1,0.5);

        }else if ($jenislaporan == 2) {

            $rpttitle= 'Laporan Bulanan Mutu Makanan Berdasarkan Tarikh Lawatan';
            
            $sqlstmt = "SELECT to_char(A.TRKHMULA,'MM/YYYY') AS tahun,
                        to_char(A.TRKHMULA,'Month') AS BULAN, B.PRGN as aktiviti,
                        count(A.IDMODULE) as jumlah
                        FROM TBLAWATAN_MAIN A
                        LEFT OUTER JOIN TBMODULE B on A.IDMODULE = B.ID
                        WHERE A.STATUS = '1' and A.IDMODULE = 'SMM' or A.IDMODULE = 'SDR' or A.IDMODULE = 'HSW' or A.IDMODULE = 'PKK'
                        and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')
                        GROUP BY to_char(A.TRKHMULA,'MM/YYYY'), to_char(A.TRKHMULA,'Month'), B.PRGN
                        ORDER BY B.PRGN , to_char(A.TRKHMULA,'MM/YYYY')";
 
            // $sum = array(
            //     // "column" => array("title"=>"JUMLAH ATR"),
            //     "row" => array("title"=>"JUMLAH"),
            //     "keys"=> array("jumlah"),
            //     "exclude" => false
            // );    
            
            $perPage = $perPage;
            $weights = array(0.1,0.8,1,1,1,1,1,0.5);
        }

        $source = Yii::$app->db->createCommand($sqlstmt)->queryAll();
        $source = json_decode(json_encode($source, JSON_NUMERIC_CHECK), true);  // Fix all Numerics
        $callback = function($row) {
            if (array_key_exists('TAHUN', $row)) {
                $row['TAHUN'] = (string)$row['TAHUN'];  // Fix strings
            }
            return $row;
        };
        
        $source = array_map($callback, $source);    //final encode
       
        $pdf = array(
            "header" =>array("title"=> $title ,"subTitle"=> $subTitle),
            "title"=> $rpttitle,
            "subTitle"=> "Dari " . str_replace("/","-",$TRKHMULA) . " Hingga " . str_replace("/","-",$TRKHTAMAT),
            //"landscape"=> true,
            "landscape"=> $landscape,
            "color" =>array("header"=> "#ffa75b","footer"=> "#ffa75b"),
            "rows"=> array("perPage"=> $perPage),
            "fontSize" => 7,
            "weights" => $weights,
            "printedBy" => Yii::$app->user->identity->NOKP,
            "paginationFormatString" => "Mukasurat {0} dari {1}",
            "align" => array(0=> "center", 1=> "center", 2=> "center", 3=> "center", 4=> "center", 5=> "center", 6=> "center", 7=> "center"), //make text align center for each column
        );     
        
        $config = array("pdf"=> $pdf, "sequenceTitle" => "NO");

        if ($sum == '') {
            //$config = array("pdf"=> $pdf, "sequenceTitle" => "NO");
        }else{
            $config['sum'] = $sum;
            //$config = array("sum"=>$sum , "pdf"=> $pdf);   
        }
        
        return [
            'config' =>  $config,
            'data' => $source,
        ];
    } 
    
    //laporan premis makanan
    public function actionGetPremisMakanan($jenislaporan, $TRKHMULA, $TRKHTAMAT)
    {
        $sqlstmt = '';

        $title = "Sistem Jabatan Perkhidmatan dan Persekitaran (eJKP)";
        $subTitle = "Majlis Bandaraya Petaling Jaya" . "\n" . "(MBPJ)";  
        $perPage = 38;
        $weights = '';
        $sum = '';  
        $landscape = false;  

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($jenislaporan == 1){ 

            $rpttitle= 'Laporan Terperinci Premis Makanan Berdasarkan Tarikh Lawatan';

            $sqlstmt = "SELECT A.NOSIRI, A.TRKHMULA AS tarikhlawatan, D.PRGN as aktiviti, 
                        NVL(A.PRGNLOKASI, '-') as lokasi, NVL(C.NOLESEN, '-') as nolesen, NVL(C.NOSSM, '-') as NoSSM -- if data is null (Oracle use NVL)
                        FROM TBLAWATAN_MAIN A 
                        LEFT OUTER JOIN TBPARAMETER_DETAIL B on A.ID_TUJUAN = B.KODDETAIL and B.KODJENIS=22   --id_tujuan merujuk pada kodjenis 22(tujuanlawatan) tetapi koddetail dimasukkan ke A.             
                        LEFT OUTER JOIN TBLAWATAN_PEMILIK C on A.NOSIRI = C.NOSIRI
                        LEFT OUTER JOIN TBMODULE D on A.IDMODULE = D.ID
                        WHERE A.STATUS = '1' and A.IDMODULE = 'PPM' OR A.IDMODULE = 'APB'
                        and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')";

            $perPage = 38;
            $weights = array(0.1,0.8,1,1,1,1,1,0.5);

        }else if ($jenislaporan == 2) {

            $rpttitle= 'Laporan Bulanan Premis Makanan Berdasarkan Tarikh Lawatan';
            
            $sqlstmt = "SELECT to_char(A.TRKHMULA,'MM/YYYY') AS tahun,
                        to_char(A.TRKHMULA,'Month') AS BULAN, B.PRGN as aktiviti,
                        count(A.IDMODULE) as jumlah
                        FROM TBLAWATAN_MAIN A
                        LEFT OUTER JOIN TBMODULE B on A.IDMODULE = B.ID
                        WHERE A.STATUS = '1' and A.IDMODULE = 'SMM' or A.IDMODULE = 'SDR' or A.IDMODULE = 'HSW' or A.IDMODULE = 'PKK'
                        and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
                        and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')
                        GROUP BY to_char(A.TRKHMULA,'MM/YYYY'), to_char(A.TRKHMULA,'Month'), B.PRGN
                        ORDER BY B.PRGN , to_char(A.TRKHMULA,'MM/YYYY')";
 
            // $sum = array(
            //     // "column" => array("title"=>"JUMLAH ATR"),
            //     "row" => array("title"=>"JUMLAH"),
            //     "keys"=> array("jumlah"),
            //     "exclude" => false
            // );    
            
            $perPage = $perPage;
            $weights = array(0.1,0.8,1,1,1,1,1,0.5);
        }

        $source = Yii::$app->db->createCommand($sqlstmt)->queryAll();
        $source = json_decode(json_encode($source, JSON_NUMERIC_CHECK), true);  // Fix all Numerics
        $callback = function($row) {
            if (array_key_exists('TAHUN', $row)) {
                $row['TAHUN'] = (string)$row['TAHUN'];  // Fix strings
            }
            return $row;
        };
        
        $source = array_map($callback, $source);    //final encode
       
        $pdf = array(
            "header" =>array("title"=> $title ,"subTitle"=> $subTitle),
            "title"=> $rpttitle,
            "subTitle"=> "Dari " . str_replace("/","-",$TRKHMULA) . " Hingga " . str_replace("/","-",$TRKHTAMAT),
            //"landscape"=> true,
            "landscape"=> $landscape,
            "color" =>array("header"=> "#ffa75b","footer"=> "#ffa75b"),
            "rows"=> array("perPage"=> $perPage),
            "fontSize" => 7,
            "weights" => $weights,
            "printedBy" => Yii::$app->user->identity->NOKP,
            "paginationFormatString" => "Mukasurat {0} dari {1}",
            "align" => array(0=> "center", 1=> "center", 2=> "center", 3=> "center", 4=> "center", 5=> "center", 6=> "center", 7=> "center"), //make text align center for each column
        );     
        
        $config = array("pdf"=> $pdf, "sequenceTitle" => "NO");

        if ($sum == '') {
            //$config = array("pdf"=> $pdf, "sequenceTitle" => "NO");
        }else{
            $config['sum'] = $sum;
            //$config = array("sum"=>$sum , "pdf"=> $pdf);   
        }
        
        return [
            'config' =>  $config,
            'data' => $source,
        ];
 
    
    } 
    
            

    // /**
    //  * Action from Laporan Kawalan Perniagaan.
    //  * Get data from index page and display to file laporan_pegerai.
    //  * Display data.
    //  */
    // public function actionGetPremisMakanan($jenislaporan, $TRKHMULA, $TRKHTAMAT)
    // {
        
    //     //Laporan terperinci
    //     if ($jenislaporan == 1){ 

    //         //declare variable for query
    //         $sqlstmt = '';

    //         $title = "Sistem Jabatan Perkhidmatan dan Persekitaran (eJKP)";
    //         $subTitle = "Majlis Bandaraya Petaling Jaya" . "\n" . "(MBPJ)";  

    //         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //         $rpttitle= 'Laporan Terperinci Premis Makanan Berdasarkan Tarikh Lawatan';

    //         $sqlstmt = "SELECT A.NOSIRI, A.TRKHMULA AS tarikhlawatan, D.PRGN as aktiviti, 
    //                     NVL(A.PRGNLOKASI, '-') as lokasi, NVL(C.NOLESEN, '-') as nolesen, NVL(C.NOSSM, '-') as NoSSM-- if data is null (Oracle use NVL)
    //                     --display status(name) aktif instead of 1
    //                     -- case
    //                     --     when A.STATUS = 1 then 'Aktif'
    //                     --     else 'Tidak Aktif'
    //                     -- end as status
    //                     FROM TBLAWATAN_MAIN A 
    //                     LEFT OUTER JOIN TBPARAMETER_DETAIL B on A.ID_TUJUAN = B.KODDETAIL and B.KODJENIS=22   --id_tujuan merujuk pada kodjenis 22(tujuanlawatan) tetapi koddetail dimasukkan ke A.             
    //                     LEFT OUTER JOIN TBLAWATAN_PEMILIK C on A.NOSIRI = C.NOSIRI
    //                     LEFT OUTER JOIN TBMODULE D on A.IDMODULE = D.ID
    //                     WHERE A.STATUS = '1' and A.IDMODULE = 'PPM' OR A.IDMODULE = 'APB'
    //                     and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
    //                     and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')";

            
    //     $perPage = 38;
    //     $weights = array(0.1,0.8,1,1,1,1,1,0.5);

    //     } else if ($jenislaporan == 2) {

    //     $rpttitle= 'Laporan Bulanan Mutu Makanan Berdasarkan Tarikh Lawatan';

    //     $sqlstmt = "SELECT to_char(A.TRKHMULA,'MM/YYYY') AS tahun,
    //                 to_char(A.TRKHMULA,'Month') AS BULAN, B.PRGN as aktiviti,
    //                 count(A.IDMODULE) as jumlah
    //                 FROM TBLAWATAN_MAIN A
    //                 LEFT OUTER JOIN TBMODULE B on A.IDMODULE = B.ID
    //                 WHERE A.STATUS = '1' and A.IDMODULE = 'PPM' OR A.IDMODULE = 'APB'
    //                 and A.TRKHMULA >= TO_DATE('$TRKHMULA 00:00:00', 'DD/MM/YYYY HH24:MI:SS')
    //                 and A.TRKHTAMAT <= TO_DATE('$TRKHTAMAT 23:59:59', 'DD/MM/YYYY HH24:MI:SS')
    //                 GROUP BY to_char(A.TRKHMULA,'MM/YYYY'), to_char(A.TRKHMULA,'Month'), B.PRGN
    //                 ORDER BY B.PRGN , to_char(A.TRKHMULA,'MM/YYYY')";

    //     // $sum = array(
    //     //     // "column" => array("title"=>"JUMLAH ATR"),
    //     //     "row" => array("title"=>"JUMLAH"),
    //     //     "keys"=> array("jumlah"),
    //     //     "exclude" => false
    //     // );    

    //     $perPage = $perPage;
    //     $weights = array(0.1,0.8,1,1,1,1,1,0.5);
    //     }

    //     $source = Yii::$app->db->createCommand($sqlstmt)->queryAll();
    //     $source = json_decode(json_encode($source, JSON_NUMERIC_CHECK), true);  // Fix all Numerics
    //     $callback = function($row) {
    //     if (array_key_exists('TAHUN', $row)) {
    //         $row['TAHUN'] = (string)$row['TAHUN'];  // Fix strings
    //     }
    //     return $row;
    //     };

    //     $source = array_map($callback, $source);    //final encode

    //     $pdf = array(
    //     "header" =>array("title"=> $title ,"subTitle"=> $subTitle),
    //     "title"=> $rpttitle,
    //     "subTitle"=> "Dari " . str_replace("/","-",$TRKHMULA) . " Hingga " . str_replace("/","-",$TRKHTAMAT),
    //     //"landscape"=> true,
    //     "landscape"=> $landscape,
    //     "color" =>array("header"=> "#ffa75b","footer"=> "#ffa75b"),
    //     "rows"=> array("perPage"=> $perPage),
    //     "fontSize" => 7,
    //     "weights" => $weights,
    //     "printedBy" => Yii::$app->user->identity->NOKP,
    //     "paginationFormatString" => "Mukasurat {0} dari {1}",
    //     "align" => array(0=> "center", 1=> "center", 2=> "center", 3=> "center", 4=> "center", 5=> "center", 6=> "center", 7=> "center"), //make text align center for each column
    //     );     

    //     $config = array("pdf"=> $pdf, "sequenceTitle" => "NO");

    //     if ($sum == '') {
    //     //$config = array("pdf"=> $pdf, "sequenceTitle" => "NO");
    //     }else{
    //     $config['sum'] = $sum;
    //     //$config = array("sum"=>$sum , "pdf"=> $pdf);   
    //     }

    //     return [
    //     'config' =>  $config,
    //     'data' => $source,
    //     ];


    //     } 


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
