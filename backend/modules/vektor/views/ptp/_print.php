<?php

use common\models\Pengguna;
use common\utilities\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\modules\makanan\models\SampelMakanan;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\lawatanmain\models\LawatanPasukan;
use backend\modules\vektor\models\SasaranPtp;
// use backend\modules\vektor\models\RacunSrt;
use backend\modules\vektor\utilities\OptionHandler;
use backend\modules\vektor\models\BekasPtp;

/**
 * @var Transkolam $model
 */

$alphabet = [
    'a', 'b', 'c', 'd', 'e',
    'f', 'g', 'h', 'i', 'j',
    'k', 'l', 'm', 'n', 'o',
    'p', 'q', 'r', 's', 't',
    'u', 'v', 'w', 'x', 'y',
    'z'
];

//function to convert to words with decimal
function numberTowords($num)
{

    $ones = array(
    0 =>"",1 => "SATU",2 => "DUA",3 => "TIGA",4 => "EMPAT",5 => "LIMA",6 => "ENAM",7 => "TUJUH",8 => "LAPAN",9 => "SEMBILAN",10 => "SEPULUH",
    11 => "SEBELAS",12 => "DUA BELAS",13 => "TIGA BELAS",14 => "EMPAT BELAS",15 => "LIMA BELAS",16 => "ENAM BELAS",17 => "TUJUH BELAS",18 => "LAPAN BELAS",19 => "SEMBILAN BELAS","014" => "EMPAT BELAS");

    $tens = array(0 => "",1 => "SEPULUH",2 => "DUA PULUH",3 => "TIGA PULUH", 4 => "EMPAT PULUH", 5 => "LIMA PULUH", 6 => "ENAM PULUH", 7 => "TUJUH PULUH", 8 => "LAPAN PULUH", 9 => "SEMBILAN PULUH" ); 
    $hundreds = array( "RATUS", "RIBU", "JUTA", "BILION", "TRILLION", "QUARDRILLION" 
    ); /*limit t quadrillion */

    $num = number_format($num,2,".",",");  //5,000.00
    $num_arr = explode(".",$num); //Array ( [0] => 5,000 [1] => 00 ) 
    $wholenum = $num_arr[0]; //5,000

    $decnum = $num_arr[1]; //00

    $whole_arr = array_reverse(explode(",",$wholenum)); //000

    krsort($whole_arr,1); //1

    $rettxt = ""; 
    
    foreach($whole_arr as $key => $i){
	
        //print_r (substr($i,0,1)) . "<br>";
        
        while(substr($i,0,1)=="0")
            $i=substr($i,1,5);
      
            if($i < 20){ 
                 echo "getting:".$i; 
                $rettxt .= $ones[$i]; 
            }elseif($i < 100){ 
                if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
                if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 

            }else{ 
                if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
                if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
                if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
            } 
            
            if($key > 0){ 
                $rettxt .= " ".$hundreds[$key]." "; 
            }
        } 

    if($decnum > 0){
        $rettxt .= " DAN ";
        if($decnum < 20){
            $rettxt .= $ones[$decnum] . " SEN ";
        }elseif($decnum < 100){
            $rettxt .= $tens[substr($decnum,0,1)];
            $rettxt .= " ".$ones[substr($decnum,1,1)] . " SEN ";
        }
    }

    return $rettxt;
    }

//function to trim decimal and convert to words
function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('',"SATU","DUA","TIGA","EMPAT","LIMA","ENAM","TUJUH","LAPAN","SEMBILAN","SEPULUH",
        "SEBELAS","DUA BELAS","TIGA BELAS","EMPAT BELAS","LIMA BELAS","ENAM BELAS","TUJUH BELAS","LAPAN BELAS","SEMBILAN BELAS");
    $list2 = array('', "SEPULUH","DUA PULUH","TIGA PULUH","EMPAT PULUH", "LIMA PULUH",  "ENAM PULUH", "TUJUH PULUH",  "LAPAN PULUH", "SEMBILAN PULUH", 'SERATUS');
    $list3 = array('', "RIBU", "JUTA", "BILION", "TRILLION", 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );

    // ones = array('',"SATU","DUA","TIGA","EMPAT","LIMA","ENAM","TUJUH","LAPAN","SEMBILAN","SEPULUH",
    //     "SEBELAS","DUA BELAS","TIGA BELAS","EMPAT BELAS","LIMA BELAS","ENAM BELAS","TUJUH BELAS","LAPAN BELAS","SEMBILAN BELAS");
    
    //     $tens = array(0 => "KOSONG",1 => "SEPULUH",2 => "DUA PULUH",3 => "TIGA PULUH", 4 => "EMPAT PULUH", 5 => "LIMA PULUH", 6 => "ENAM PULUH", 7 => "TUJUH PULUH", 8 => "LAPAN PULUH", 9 => "SEMBILAN PULUH" ); 
    //     $hundreds = array( "RATUS", "RIBU", "JUTA", "BILION", "TRILLION", "QUARDRILLION" 
       
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' RATUS' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return implode(' ', $words);
}

$sasarans = $model->sasaranptp;
$bekasptp = $model->bekasptp;
foreach($bekasptp as $bekas){
    $rows[] = [
        $ai = $bekas->pencapaian['ai'],
        $bi = $bekas->pencapaian['bi'],
        $ci = $bekas->pencapaian['ci'],
    ];
}

// $ai = bcdiv($ai, 1, 2);


// var_dump($sasarans);
// exit();

$ketua = LawatanPasukan::findOne(['NOSIRI' => $model->NOSIRI, 'JENISPENGGUNA' => 1]);  
$idketua = $ketua->IDPENGGUNA;
$nama = Yii::$app->db->createCommand(" SELECT NAME FROM C##MAJLIS.PRUSER
            WHERE USERID=$idketua")->queryScalar();
$jawatan = Yii::$app->db->createCommand(" SELECT DESIGNATION FROM C##MAJLIS.PRUSER
            WHERE USERID=$idketua")->queryScalar();

$ahlis = Yii::$app->db->createCommand("SELECT COUNT(IDPENGGUNA) FROM TBLAWATAN_PASUKAN WHERE NOSIRI ='$model->NOSIRI' AND JENISPENGGUNA='2'")->queryScalar();

// $kawasan1 = BekasPtp::findOne(['NOSIRI' => $model->NOSIRI, 'KAWASAN' => 1]);  
// $kawasan2 = BekasPtp::findOne(['NOSIRI' => $model->NOSIRI, 'KAWASAN' => 2]);  

// var_dump($model->racun1->PRGN);
// exit();
?>

<style>
    .header-mid {
        text-align: center;
        font-weight: bold;
    }

    .document-title {
        text-align: center;
        font-weight: bold;
        font-size: 13px;
    }

    .content-table td {
        padding-bottom: 10px;
        vertical-align: top;
        font-size: 13px;
        /* font-family: Arial; */
    }

    .inner-table {
        margin-top: 20px;
        width: 100%;
    }

    .sampel-table td{
        margin: auto;
        border-collapse: separate;
        border: 1px solid black;
        /* width: 150px; */
        height: 25px;
        font-size: 13px;
        padding-left: 5px;
        padding-right: 5px;

    }

    .centerImage
    {
        text-align:center;
        display:block;
    }
</style>

<table>
    <tr>
        <td style="width:120px"></td>
        <td style="width:290px"></td>
        <td style="text-align:right;">
            <div style=" font-size: 11px;"> No. Ruj &nbsp;: (<?= $model->NOSIRI ?>)eDengue/KV.PPA/11.1</div>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td style="padding-left:15px">
            <div>
                <div><img height="100" src="<?= Yii::getAlias('@web/images/logo.png') ?>" /></div>
            </div>
        </td>
        <td style="width:30px"></td>
        <td style="text-align:center;font-size:16px;">
            <div>
                <h6 class="document-title">LAPORAN AKTIVITI PEMERIKSAAN PEMBIAKAN AEDES (BORANG PTP)</h6>
                <h6 class="document-title">UNIT KAWALAN VEKTOR</h6>
                <h6 class="document-title">JABATAN PERKHIDMATAN KESIHATAN & PERSEKITARAN</h6>
                <h6 class="document-title">MAJLIS BANDARAYA PETALING JAYA</h6>
            </div>
        </td>
    </tr>
</table>
<br>
<div style="padding-top:2px;text-align:justify;">
<br>
<div>
    <div>
        <table class="sampel-table">
            <tr>
                <td colspan="3" style="border-style:none;padding-left: 0px;"><h6 class="document-title" style="text-align:left;">A. MAKLUMAT KES</h6></td>      
                <td colspan="2" style="border-style:none;padding-left: 0px;"><h6 class="document-title" style="text-align:left">B. MAKLUMAT AKTIVITI</h6></td>
            </tr>
            <!-- <tr style="border-style:none;"><td></td></tr> -->
            <tr>
                <td style="width:130px;"><?= Yii::t('app', 'NAMA KES RUJUK') ?></td>
                <td style="width:200px"><b><?=$model->V_RUJUKANKES?></b></td> 
                <td style="border-style:none;width:5px"></td>      
                <td style="width:130px"><?= Yii::t('app', 'TARIKH AKTIVITI') ?></td>
                <td style="width:200px"><b><?=date('d-m-Y', strtotime($model->TRKHMULA))?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'NO. DAFTAR KES') ?></td>
                <td><b><?=$model->V_NODAFTARKES?></b></td>
                <td style="border-style:none;width:5px"></td>      
                <td><?= Yii::t('app', 'TARIKH DIMASUKKAN DATA') ?></td>
                <td><b><?=date('d-m-Y', strtotime($model->V_TRKHKEYIN))?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'NO. WABAK') ?></td>
                <td><b><?=$model->V_NOWABAK?></b></td>
                <td style="border-style:none;width:5px"></td>      
                <td><?= Yii::t('app', 'TARIKH NOTIFIKASI KES') ?></td>
                <td><b><?=date('d-m-Y H:i A', strtotime($model->V_TRKHNOTIFIKASI))?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'NEGERI') ?></td>
                <td><b>Selangor</b></td>
                <td style="border-style:none;width:5px"></td>      
                <td><?= Yii::t('app', 'MINGGU EPID') ?></td>
                <td><b><?=$model->V_MINGGUEPID?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'DAERAH') ?></td>
                <td><b>Petaling</b></td>
                <td style="border-style:none;width:5px"></td>      
                <td><?= Yii::t('app', 'KAWASAN OPERASI') ?></td>
                <td><b>PBT</b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'MUKIM') ?></td>
                <td><b><?=isset($model->mukim->PRGN) ? $model->mukim->PRGN :null?></b></td>
                <td style="border-style:none;width:5px"></td>      
                <td><?= Yii::t('app', 'NAMA PBT') ?></td>
                <td><b>MBPJ</b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'LOKALITI') ?></td>
                <td><b><?=isset($model->lokaliti->PRGN) ? $model->lokaliti->PRGN :null?></b></td>
                <td style="border-style:none;width:5px"></td>      
                <td><?= Yii::t('app', 'NAMA KETUA PASUKAN') ?></td>
                <td><b><?=$nama?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'ALAMAT') ?></td>
                <td><b><?=$model->PRGNLOKASI?></b></td>
                <td style="border-style:none;width:5px"></td>      
                <td><?= Yii::t('app', 'BIL. AHLI PASUKAN') ?></td>
                <td><b><?=$ahlis?></b></td>
            </tr>
        </table>    
    </div>
    <br>
    <h6 class="document-title" style="text-align:left">C. AKTIVITI LARVACIDING</h6>
    <!-- <br> -->
    <table>
        <tr>
            <td>
                <div style="font-size:13px"><b>A. Premis Dibubuh larvisid</b></div>
                <br>
                <table class="sampel-table">
                    <tr>
                        <td style="width:130px"><?= Yii::t('app', 'Sasaran Premis') ?></td>
                        <td style="text-align:right;width:200px"><b><?=$model->V_SASARANPREMIS1?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Premis') ?></td>
                        <td style="text-align:right;"><b><?=$model->V_BILPREMIS1?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Bekas') ?></td>
                        <td style="text-align:right;"><b><?=$model->V_BILBEKAS1?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jenis Racun') ?></td>
                        <td style="text-align:right;"><b><?= isset($model->racun1->PRGN) ? $model->racun1->PRGN:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jum. Racun (gm)') ?></td>
                        <td style="text-align:right;"><b><?=$model->V_JUMRACUN1?></b></td>
                    </tr>
                    <tr style="border-style:none">
                        <td style="border-style:none"></td>
                        <td style="border-style:none"></td>
                    </tr>
                </table>
            </td>
            <td style="width:10px"></td>
            <td>
                <div style="font-size:13px"><b>B. Premis Disembur larvisid</b></div>
                <br>
                <table class="sampel-table">
                    <tr>
                        <td style="width:130px"><?= Yii::t('app', 'Sasaran Premis') ?></td>
                        <td style="width:200px;text-align:right;"><b><?=$model->V_SASARANPREMIS2?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Premis') ?></td>
                        <td style="text-align:right;"><b><?=$model->V_BILPREMIS2?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Bekas') ?></td>
                        <td style="text-align:right;"><b><?=$model->V_BILBEKAS2?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jenis Racun') ?></td>
                        <td style="text-align:right;"><b><?=isset($model->racun2->PRGN) ? $model->racun2->PRGN:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jum. Racun (gm)') ?></td>
                        <td style="text-align:right;"><b><?=$model->V_JUMRACUN2?></b></td>
                    </tr>
                    <tr style="border-style:none">
                        <td style="border-style:none"></td>
                        <td style="border-style:none"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-size:13px"><b>C. Premis Disembur larvisid</b></div>
                <br>
                <table class="sampel-table">
                    <tr>
                        <td style="width:130px"><?= Yii::t('app', 'Bil. Orang') ?></td>
                        <td style="text-align:right;width:200px"><b><?=$model->V_BILORANG?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Premis') ?></td>
                        <td style="text-align:right;"><b><?=$model->V_BILPREMIS3?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jenis Racun') ?></td>
                        <td style="text-align:right;"><b><?=isset($model->racun3->PRGN) ? $model->racun3->PRGN:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jum. Racun (gm)') ?></td>
                        <td style="text-align:right;"><b><?=$model->V_JUMRACUN3?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Nama KK dsb Diagihkan') ?></td>
                        <td><b><?=$model->PTP_NAMAKK?></b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <p style="page-break-after: always"></p>
    <h6 class="document-title" style="text-align:left">D. TUJUAN AKTIVITI</h6>
    <!-- <br> -->
    <div style="margin-left:30px;font-size:13px">Jenis Pemeriksaan &nbsp; : <b><?= OptionHandler::resolve('jenis-sembur', $model->V_JENISSEMBUR) ?></b></div>
    <br>
    <?php
        if($model->V_JENISSEMBUR == 1){
            echo "<div style='margin-left:30px;font-size:13px'>". "Kategori Lokaliti ". "&nbsp; &nbsp; &nbsp;: " . "<b>" . OptionHandler::resolve('kat-lokaliti', $model->V_KATLOKALITI) . "</b>" ."</div>";
            echo "<br>";
            echo "<div style='margin-left:30px;font-size:13px'>". "Pusingan &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: "."<b>" .$model->V_PUSINGAN. "</b>" ."</div>";
        }else{
            echo "<div style='margin-left:30px;font-size:13px'>". "Surveilan &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ". "<b>" .OptionHandler::resolve('ptp-surveilan', $model->V_ID_SUREVEILAN). "</b>" ."</div>";
        }
    ?>
    <br>
    <h6 class="document-title" style="text-align:left"><b>Tempoh Masa Pemeriksaan Dilakukan Selepas Notifikasi Kes</b></h6>
    <table style="margin-left:30px">
        <tr>
            <td>
                <div>Tempoh Masa : <b><?=OptionHandler::resolve('tempoh', $model->V_TEMPOH)?></b></div>
            <td>
            <td style="width:50px"></td>
            <?php
                if($model->V_TEMPOH == 2){
                    echo "<td>". "Alasan : " . "<b>" . $model->alasan->PRGN . "</b>" ."</td>";
                }
            ?>
        </tr>
    </table>
    <br>
    <!-- <p style="page-break-after: auto"></p> -->
    <h6 class="document-title" style="text-align:left">E. PENCAPAIAN LIPUTAN PREMIS PPA PADA KESELURUHAN</h6>
    <h6 class="document-title" style="text-align:left;padding-left:15px">SASARAN JUMLAH JENIS PREMIS DALAM LINKUNGAN 50 M</h6>
    <table class="sampel-table">
        <thead>
            <tr>
                <td style="width:280px" rowspan="2">Jenis Premis </td>
                <td style="width:250px;text-align:center" colspan="4">Jumlah Premis</td>
            </tr>
            <tr>
                <td>Sasaran (50 M)</td>
                <td>Pencapaian</td>
                <td>Peratusan (%)</td>
                <td>Jumlah Positif</td>
            </tr>        
        </thead>
        <tr>
            <?php 
                if($model){
                    foreach($sasarans as $sasaran){
                        echo "<tr>";
                            echo "<td style='font-weight: bold;'>". $sasaran->premis1->PRGN ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". $sasaran->SASARAN ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". $sasaran->PENCAPAIAN ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". " " ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". $sasaran->JUMPOSITIF ."</td>";
                        echo "</tr>";
                    }	
                }							
            ?>        
        </tr>
        <tr class="sampel-table">
            <td><b><?= Yii::t('app', 'Jumlah Premis') ?></b></td>
            <?php 
                if($sasarans){
                    echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran['sasaran'] ."</td>";
                    echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran['pencapaian'] ."</td>";
                    echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran['peratusan'] ."</td>";
                    echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran['positif'] ."</td>";
                }
            ?>
        </tr>
        <tr class="sampel-table">
            <td style="text-align:right;" colspan="4"><b><?= Yii::t('app', 'Bil. Penduduk') ?></b></td>
            <td style="text-align:right;" ><b><?=$model->V_BILPENDUDUK?></b></td>
        </tr>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left">F. LIPUTAN PREMIS PPA DALAM DAN LUAR RUMAH</h6>
    <table class="sampel-table" style="width:100%">
            <tr>
                <tr>
                    <td style="text-align:left"><b><?= Yii::t('app', 'JENIS LIPUTAN') ?></b></td>
                    <td style="text-align:left"><b><?= Yii::t('app', 'KAWASAN LIPUTAN') ?></b></td>
                    <td style="text-align:left"><b><?= Yii::t('app', 'JUMLAH PREMIS') ?></b></td>
                </tr>
                <tr>
                    <td rowspan="2"><?= Yii::t('app', 'PEMERIKSAAN TIDAK LENGKAP') ?></td>
                    <td><?= Yii::t('app', 'Dalam Rumah Sahaja') ?></td>
                    <td style="text-align:right"><b><?=$model->V_TLENGKAPDALAM?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Luar Rumah Sahaja') ?></td>
                    <td style="text-align:right"><b><?=$model->V_TLENGKAPLUAR?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'PEMERIKSAAN LENGKAP') ?></td>
                    <td><?= Yii::t('app', 'Dalam dan Luar Rumah') ?></td>
                    <td style="text-align:right"><b><?=$model->V_LENGKAP?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'TIDAK DIPERIKSA') ?></td>
                    <td><?= Yii::t('app', '') ?></td>
                    <td style="text-align:right"><b><?=$model->V_TPERIKSA?></b></td>
                </tr>
            </tr>
            <tr class="sampel-table">
                <td style="text-align:right;" colspan="2"><b><?= Yii::t('app', 'JUMLAH PREMIS') ?></b></td>
                <?php 
                    if($sasarans){
                        echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran['pencapaian'] ."</td>";
                    }
                ?>        
            </tr>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left">G. SEBAB PREMIS TIDAK DIPERIKSA</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
            <tr>
                <tr>
                    <td style="width:70%; text-align:left"><b><?= Yii::t('app', 'ALASAN') ?></b></td>
                    <td style="width:30%; text-align:left"><b><?= Yii::t('app', 'JUMLAH PREMIS') ?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Penduduk Enggan') ?></td>
                    <td style="text-align:right"><b><?=$model->V_SB1?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Premis Tutup') ?></td>
                    <td style="text-align:right"><b><?=$model->V_SB3?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Premis Kosong') ?></td>
                    <td style="text-align:right"><b><?=$model->V_SB2?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Lain-lain') ?></td>
                    <td style="text-align:right"><b><?=$model->V_SB4?></b></td>
                </tr>
            </tr>
        </thead>
    </table>
    <br>
    <p style="page-break-after: always"></p>
    <h6 class="document-title" style="text-align:left">H. JENIS-JENIS BEKAS DIPERIKSA</h6>
    <br>
    <table>
        <tr>
            <td>
                <?php if(!empty($model->bekas1)): ?>
                <h6 class="document-title" style="text-align:left;font-size:12px">PEMBIAKAN DALAM RUMAH</h6>
                <br>
                <table class="sampel-table">
                    <tr>
                        <td style="height:15px;"><?= Yii::t('app', 'Bil') ?></td>
                        <td style="width:150px"><?= Yii::t('app', 'Jenis Bekas') ?></td>
                        <td style="width:20px"><?= Yii::t('app', 'Bil. Bekas') ?></td>
                        <td style="width:50px"><?= Yii::t('app', 'Bil. Bekas Potensi Pembiakan (Bertakung Air)') ?></td>
                        <td style="width:50px"><?= Yii::t('app', 'Bil. Bekas Positif Pembiakan') ?></td>
                        <td style="width:50px"><?= Yii::t('app', 'Keputusan Pembiakan (Spesis Nyamuk)') ?></td>
                        <td style="width:110px"><?= Yii::t('app', 'Positif Purpa') ?></td>
                        <td style="width:100px"><?= Yii::t('app', 'Catatan') ?></td>				
                    </tr>
                    <tr>
                        <?php 
                        if($model){
                            $i=0;
                            foreach($model->bekas1 as $jenisbekas){
                                echo "<tr>";
                                    $i = $i+1;

                                    echo "<td style='height:30px'>". $i ."</td>";
                                    echo "<td style='font-weight: bold;'>". $jenisbekas->JENISBEKAS ."</td>";
                                    echo "<td style='font-weight: bold;text-align:right;'>". $jenisbekas->BILBEKAS ."</td>";
                                    echo "<td style='font-weight: bold;text-align:right;'>". $jenisbekas->BILPOTENSI ."</td>";
                                    echo "<td style='font-weight: bold;text-align:right'>". $jenisbekas->BILPOSITIF ."</td>";
                                    echo "<td style='font-weight: bold;'>". $jenisbekas->KEPUTUSAN."</td>";
                                    echo "<td style='font-weight: bold;'>". $jenisbekas->PURPA ."</td>";
                                    echo "<td style='font-weight: bold;'>". $jenisbekas->CATATAN ."</td>";
                                echo "</tr>";
                            }	
                        }							
                        ?>        
                    </tr>
                    <tr class="sampel-table">
                        <!-- <td style="height:30px"><b></b></td> -->
                        <td style="height:30px" colspan="2"><b>JUMLAH</b></td>
                        <td style="text-align:right"><b><?=$bekas->pencapaian['bilbekas1']?></b></td>
                        <td style="text-align:right"><b><?=$bekas->pencapaian['bilpotensi1']?></b></td>
                        <td style="text-align:right"><b><?=$bekas->pencapaian['bilpositif1']?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <?php endif; ?>
            </td>
        </tr>
        <br>
        <br>
        <tr>
            <td>
                <?php if(!empty($model->bekas2)): ?>
                    <h6 class="document-title" style="text-align:left;font-size:12px">PEMBIAKAN LUAR RUMAH</h6>
                    <br>
                    <table class="sampel-table">
                        <tr>
                            <td style="height:15px;"><?= Yii::t('app', 'Bil') ?></td>
                            <td style="width:150px"><?= Yii::t('app', 'Jenis Bekas') ?></td>
                            <td style="width:20px"><?= Yii::t('app', 'Bil. Bekas') ?></td>
                            <td style="width:50px"><?= Yii::t('app', 'Bil. Bekas Potensi Pembiakan (Bertakung Air)') ?></td>
                            <td style="width:50px"><?= Yii::t('app', 'Bil. Bekas Positif Pembiakan') ?></td>
                            <td style="width:50px"><?= Yii::t('app', 'Keputusan Pembiakan (Spesis Nyamuk)') ?></td>
                            <td style="width:110px"><?= Yii::t('app', 'Positif Purpa') ?></td>
                            <td style="width:100px"><?= Yii::t('app', 'Catatan') ?></td>				
                        </tr>
                        <tr>
                            <?php 
                            if($model){
                                $i=0;
                                foreach($model->bekas2 as $jenisbekas){
                                    echo "<tr>";
                                        $i = $i+1;

                                        echo "<td style='height:30px'>". $i ."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->JENISBEKAS ."</td>";
                                        echo "<td style='font-weight: bold;text-align:right;'>". $jenisbekas->BILBEKAS ."</td>";
                                        echo "<td style='font-weight: bold;text-align:right;'>". $jenisbekas->BILPOTENSI ."</td>";
                                        echo "<td style='font-weight: bold;text-align:right'>". $jenisbekas->BILPOSITIF ."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->KEPUTUSAN."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->PURPA ."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->CATATAN ."</td>";
                                    echo "</tr>";
                                }	
                            }							
                            ?>        
                        </tr>
                        <tr class="sampel-table">
                            <!-- <td style="height:30px"><b></b></td> -->
                            <td style="height:30px" colspan="2"><b>JUMLAH</b></td>
                            <td style="text-align:right"><b><?=$bekas->pencapaian['bilbekas2']?></b></td>
                            <td style="text-align:right"><b><?=$bekas->pencapaian['bilpotensi2']?></b></td>
                            <td style="text-align:right"><b><?=$bekas->pencapaian['bilpositif2']?></b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                <?php endif; ?>
            </td>
        </tr>
        <br>
        <tr>
            <td><b>Bilangan Bekas Dimusnahkan : <?=$model->PTP_BILBEKASMUSNAH?></b></td>
        </tr>
    </table>
    <br>
    <p style="page-break-after: always"></p>
    <h6 class="document-title" style="text-align:left">I. PENGIRAAN PENCAPAIAN</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
            <tr>
                <tr>
                    <td><?= Yii::t('app', '1% Liputan Premis Diperiksa Lengkap (Dalam & Luar Rumah)') ?></td>
                    <td style="text-align:right"><b></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Indeks Aedes (AI)') ?></td>
                    <td style="text-align:right"><b><?=bcdiv($ai, 1, 2)?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Indeks Breteau (BI)') ?></td>
                    <td style="text-align:right"><b><?=bcdiv($bi, 1, 2)?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Indeks Bekas (CI)') ?></td>
                    <td style="text-align:right"><b><?=bcdiv($ci, 1, 2)?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Indeks Purpa (HPI)') ?></td>
                    <td style="text-align:right"><b></b></td>
                </tr>
            </tr>
        </thead>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left">J. NAMA AHLI PASUKAN</h6>
    <table class="sampel-table" style="width:100%">
        <tr>
            <td style="width:60%"><?= Yii::t('app', 'Nama Anggota') ?></td>
            <td style="width:40%"><?= Yii::t('app', 'Jawatan') ?></td>
        </tr>
        <tr>
            <?php 
                if($model){
                    foreach($model->ahli as $pasukan){
                        echo "<tr>";
                            echo "<td style='font-weight: bold;'>". $pasukan->pengguna0->NAMA ."</td>";
                            echo "<td style='font-weight: bold;'>". $pasukan->jawatan ."</td>";
                        echo "</tr>";
                    }	
                }							
            ?>        
        </tr>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left">J. MAKLUMAT PENCATAT</h6>
    <table class="sampel-table" style="width:100%">
        <tr>
            <td style="width:60%"><?= Yii::t('app', 'Nama Pegawai') ?></td>
            <td style="width:40%"><?= Yii::t('app', 'Jawatan') ?></td>
        </tr>
        <tr>
            <td style="width:60%"><b><?= $nama ?></b></td>
            <td style="width:40%"><b><?= $jawatan?></b></td>
        </tr>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left;font-size:13px;">LAIN-LAIN CATATAN</h6>
    <div style="font-size:13px;">Catatan : <?=$model->CATATAN?></div>
</div>








