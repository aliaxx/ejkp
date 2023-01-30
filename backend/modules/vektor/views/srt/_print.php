<?php

use common\models\Pengguna;
use common\utilities\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\modules\makanan\models\SampelMakanan;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\lawatanmain\models\LawatanPasukan;
use backend\modules\vektor\models\SasaranSrt;
use backend\modules\vektor\models\RacunSrt;

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

$sasarans = $model->sasaransrt;
// foreach($sasarans as $sasaran){
//     $rows[] = [
//         $sasaran->NOSIRI,    
//     ];
// }

$sasaransrt = SasaranSrt::findAll(['NOSIRI' => $model->NOSIRI]);  
foreach ($sasaransrt as $sasaransrt1) {
    $rows[] = [
        $sasaransrt1->ID_JENISPREMIS,
    ];
}

$racundalam1 = RacunSrt::findAll(['NOSIRI' => $model->NOSIRI, 'ID_PENGGUNAANRACUN' => 3]);  
foreach ($racundalam1 as $racundalam) {
    $rows[] = [
        $racundalam->ID_JENISRACUNSRTULV,
    ];
}

$racunluar1 = RacunSrt::findAll(['NOSIRI' => $model->NOSIRI, 'ID_PENGGUNAANRACUN' => 2]);  
foreach ($racunluar1 as $racunluar) {
    $rows[] = [
        $racunluar->ID_JENISRACUNSRTULV,
    ];
}

$dalamluar1 = RacunSrt::findAll(['NOSIRI' => $model->NOSIRI, 'ID_PENGGUNAANRACUN' => 1]);  
foreach ($dalamluar1 as $dalamluar) {
    $rows[] = [
        $dalamluar->ID_JENISRACUNSRTULV,
    ];
}
// var_dump($dalamluar->jenisPelarut->PRGN);
// exit();

// $jumlah = Yii::$app->db->createCommand("SELECT PENCAPAIAN1 + PENCAPAIAN2 FROM TBSASARAN_SRT WHERE NOSIRI ='$sasaransrt1->NOSIRI' AND ID_JENISPREMIS='$sasaransrt1->ID_JENISPREMIS'")->queryAll();
// foreach ($jumlah as $jumlah1) {
//     $rows[] = [
//         $sasaransrt1->ID_JENISPREMIS,
//     ];
// }


// var_dump($sasaran->NOSIRI);
// exit();

// $pencapaian1 = Yii::$app->db->createCommand("SELECT ((PENCAPAIAN1 + PENCAPAIAN2)/JUMPREMIS)*100 FROM TBSASARAN_SRT WHERE NOSIRI ='$sasaransrt1->NOSIRI' AND ID_JENISPREMIS='$sasaransrt1->ID_JENISPREMIS'")->queryOne();
// $pencapaian = implode('', $pencapaian1); 
// $jumlah = $sasaransrt1->PENCAPAIAN1 + $sasaransrt1->PENCAPAIAN2;
// $jumlah = Yii::$app->db->createCommand("SELECT PENCAPAIAN1 + PENCAPAIAN2 FROM TBSASARAN_SRT WHERE NOSIRI ='$sasaran->NOSIRI'")->queryScalar();
// $jumlahs = implode('', $jumlah); 

// $sumjumpremis = SUM($sasaransrt1->JUMPREMIS);
// $sumjumpremis = Yii::$app->db->createCommand("SELECT sum(JUMPREMIS) FROM TBSASARAN_SRT WHERE NOSIRI ='$sasaransrt1->NOSIRI'")->queryScalar();
// $sumpencapaian1 = Yii::$app->db->createCommand("SELECT sum(PENCAPAIAN1) FROM TBSASARAN_SRT WHERE NOSIRI ='$sasaransrt1->NOSIRI'")->queryScalar();
// $sumpencapaian2 = Yii::$app->db->createCommand("SELECT sum(PENCAPAIAN2) FROM TBSASARAN_SRT WHERE NOSIRI ='$sasaransrt1->NOSIRI'")->queryScalar();

// var_dump($sumjumpremis);
// exit();

// $jumpremis = Yii::$app->db->createCommand("SELECT SUM(JUMPREMIS) FROM TBSASARAN_SRT WHERE NOSIRI = '$sasaransrt1->NOSIRI'")->queryScalar();
// $jumpremis = implode('', $jum); 



$pasukan1 = LawatanPasukan::findAll(['NOSIRI' => $model->NOSIRI, 'JENISPENGGUNA' => 2]);  
foreach ($pasukan1 as $pasukan) {
    $rows[] = [
        $pasukan->IDPENGGUNA,
        $pasukan->JENISPENGGUNA,
    ];
}

$ahlis = Yii::$app->db->createCommand("SELECT COUNT(IDPENGGUNA) FROM TBLAWATAN_PASUKAN WHERE NOSIRI ='$model->NOSIRI' AND JENISPENGGUNA='2'")->queryScalar();
// $ahlis = implode('', $ahli);  

// $pgndaftar = $model->PGNDAFTAR;
$id = $pasukan->IDPENGGUNA;
$get_data = Yii::$app->db->createCommand(" SELECT DESIGNATION FROM C##MAJLIS.PRUSER
            WHERE USERID=$id")->queryAll();
foreach ($get_data as $get_datas) {
    $rows[] = [
        !empty($get_datas->DESIGNATION),
    ];
}



// $jawatan = implode('', $get_data);      

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
            <div style=" font-size: 11px;"> No. Ruj &nbsp;: (<?= $model->NOSIRI ?>)MBPJ-MPK(U/KS)-01.B02</div>
        </td>
    </tr>
</table>
<br>
<table>
    <tr>
        <td style="padding-left:15px">
            <div>
                <div><img height="100" src="<?= Yii::getAlias('@web/images/logo.png') ?>" /></div>
            </div>
        </td>
        <td style="width:60px"></td>
        <td style="text-align:center;font-size:16px;">
            <div>
                <h6 class="document-title">LAPORAN AKTIVITI SEMBURAN TERMAL (BORANG SRT)</h6>
                <h6 class="document-title">UNIT KAWALAN VEKTOR</h6>
                <h6 class="document-title">JABATAN PERKHIDMATAN KESIHATAN & PERSEKITARAN</h6>
                <h6 class="document-title">MAJLIS BANDARAYA PETALING JAYA</h6>
            </div>
        </td>
    </tr>
</table>

<div style="padding-top:2px;text-align:justify;">
<br>
<div>
    <h6 class="document-title" style="text-align:left">A. MAKLUMAT KES/AKTIVITI</h6>
    <!-- <br> -->
    <table class="sampel-table">
        <tr>
            <td style="width:130px;"><?= Yii::t('app', 'NAMA KES RUJUK') ?></td>
            <td style="font-size:14px;width:200px"><b><?=$model->V_RUJUKANKES?></b></td>
            <!-- <td style="border-style:none;width:5px"></td>       -->
            <td><?= Yii::t('app', 'TARIKH AKTIVITI') ?></td>
            <td style="font-size:14px;width:200px"><b><?=date('d-m-Y', strtotime($model->TRKHMULA))?></b></td>
        </tr>  
        <tr>
            <td><?= Yii::t('app', 'NO. WABAK') ?></td>
            <td style="font-size:14px"><b><?=$model->V_NOWABAK?></b></td>
            <!-- <td style="border-style:none;width:5px"></td>       -->
            <td><?= Yii::t('app', 'NO. AKTIVITI') ?></td>
            <td style="font-size:14px"><b><?=$model->V_NOAKTIVITI?></b></td>
        </tr>
        <tr>
            <td><?= Yii::t('app', 'NEGERI') ?></td>
            <td style="font-size:14px"><b>Selangor</b></td>
            <!-- <td style="border-style:none;width:5px"></td>       -->
            <td><?= Yii::t('app', 'NAMA KETUA PASUKAN') ?></td>               
            <td style="font-size:14px"><b><?=$pasukan->pengguna0->NAMA?></b></td>
        </tr>
        <tr>
            <td><?= Yii::t('app', 'DAERAH') ?></td>
            <td style="font-size:14px"><b>Petaling</b></td>
            <!-- <td style="border-style:none;width:5px"></td>       -->
            <td><?= Yii::t('app', 'BILANGAN AHLI PASUKAN') ?></td>
            <td style="font-size:14px"><b><?=$ahlis?></b></td>
        </tr>
        <tr>
            <td><?= Yii::t('app', 'ALAMAT') ?></td>
            <td style="font-size:14px" colspan="3"><b><?=$model->PRGNLOKASI?></b></td>
        </tr>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left">B. SASARAN JUMLAH JENIS PREMIS DALAM LINGKUNGAN 200 M</h6>
    <table class="sampel-table">
        <tr>
            <td style="text-align:center;width:200px"><b>JENIS PREMIS</b></td>
            <td style=";text-align:center;width:100px"><b>JUM. PREMIS (200 M)</b></td>
            <td style="text-align:center;width:100px"><b>PENCAPAIAN (200 M)</b></td>
            <td style="text-align:center;width:100px"><b>PENCAPAIAN (>200 M)</b></td>
            <td style="text-align:center;width:100px"><b>JUMLAH</b></td>
            <td style="text-align:center;width:100px"><b>% PENCAPAIAN</b></td>
        </tr>  
        <tr>
            <?php 
                if($model){
                    foreach($sasarans as $sasaran){
                        echo "<tr>";
                            echo "<td style='font-weight: bold;'>". $sasaran->premis1->PRGN ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". $sasaran->JUMPREMIS ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". $sasaran->PENCAPAIAN1 ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". $sasaran->PENCAPAIAN2 ."</td>";
                            echo "<td style='font-weight: bold;'>". ' ' ."</td>";
                            echo "<td style='font-weight: bold;'>". ' ' ."</td>";
                        echo "</tr>";
                    }	
                }							
            ?>        
        </tr>
        <tr class="sampel-table">
            <td><b><?= Yii::t('app', 'Jumlah Premis') ?></b></td>
            <td style="text-align:right"><b><?= isset($sasaran->jumSasaran['sumjumpremis']) ? $sasaran->jumSasaran['sumjumpremis'] : null ?></b></td>
            <td style="text-align:right"><b><?=isset($sasaran->jumSasaran['sumpencapaian1']) ? $sasaran->jumSasaran['sumpencapaian1']: null ?></b></td>
            <td style="text-align:right"><b><?=isset($sasaran->jumSasaran['sumpencapaian2']) ? $sasaran->jumSasaran['sumpencapaian2'] : null ?></b></td>
            <td style="text-align:right"><b></b></td>
            <td style="text-align:right"><b></b></td>
        </tr>
        <tr class="sampel-table">
            <td style="text-align:right;" colspan="5"><b><?= Yii::t('app', 'Bil. Penduduk') ?></b></td>
            <td style="text-align:right;"><b><?=$model->V_BILPENDUDUK?></b></td>
        </tr>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left">C. JENIS SEMBURAN</h6>
    <!-- 251122 - error data on null -->
    <div style="margin-left:30px;font-size:13px">JENIS SEMBURAN : <b><?=isset($model->jenisSemburan->PRGN) ? $model->jenisSemburan->PRGN:null?></b></div>
    <br>
    <h6 class="document-title" style="text-align:left">D. MASA SEMBURAN</h6>
    <div style="margin-left:30px;font-size:13px">MASA MULA SEMBURAN : <b><?=date('H:i A', strtotime($model->TRKHMULA))?>&nbsp; &nbsp;</b> MASA TAMAT SEMBURAN : <b><?=date('H:i A', strtotime($model->TRKHTAMAT))?></b></div>
    <br>
    <p style="page-break-after: always"></p>
    <h6 class="document-title" style="text-align:left">E. LIPUTAN PENGABUTAN</h6>
    <table class="sampel-table" style="width:100%">
        <tr>
            <tr>
                <td style="width:40%; text-align:left"><b><?= Yii::t('app', 'JENIS PENGABUTAN') ?></b></td>
                <td style="width:40%; text-align:left"><b><?= Yii::t('app', 'LIPUTAN PENGABUTAN') ?></b></td>
                <td style="width:20%; text-align:left"><b><?= Yii::t('app', 'JUMLAH PREMIS') ?></b></td>
            </tr>
            <tr>
                <td rowspan="2"><?= Yii::t('app', 'PENGABUTAN TIDAK LENGKAP') ?></td>
                <td><?= Yii::t('app', 'Dalam Rumah Sahaja') ?></td>
                <td style="text-align:right"><b><?=$model->V_TLENGKAPDALAM?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Luar Rumah Sahaja') ?></td>
                <td style="text-align:right"><b><?=$model->V_TLENGKAPLUAR?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'PENGABUTAN LENGKAP') ?></td>
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
            <!-- <td style="text-align:right;"><b><?= isset($sasaran->jumSasaran['jumpremis']) ? $sasaran->jumSasaran['jumpremis'] : null ?></b></td> -->
            <td style="text-align:right;"><b><?= isset($sasaran->jumSasaran['sumjumpremis']) ? $sasaran->jumSasaran['sumjumpremis'] : null ?></b></td>
        </tr>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left">F. SEBAB PREMIS TIDAK DISEMBUR DAN TIDAK DISEMBUR LENGKAP</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
            <tr>
                <tr>
                    <td style="width:80%; text-align:left"><b><?= Yii::t('app', 'ALASAN') ?></b></td>
                    <td style="width:20%; text-align:left"><b><?= Yii::t('app', 'JUMLAH PREMIS') ?></b></td>
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
    <h6 class="document-title" style="text-align:left">G. PENGGUNAAN RACUN SERANGGA</h6>
    <h6 class="document-title" style="text-align:left;font-size:12px">1. PENGGUNAAN RACUN SERANGGA (DALAM & LUAR RUMAH)</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
                <tr>
                    <td style="width:30%"><?= Yii::t('app', 'Jenis Racun Serangga') ?></td>
                    <td style="width:10%"><?= Yii::t('app', 'Amaun Racun (ml)') ?></td>
                    <td style="width:20%"><?= Yii::t('app', 'Jenis Pelarut') ?></td>
                    <td style="width:10%"><?= Yii::t('app', 'Amaun Pelarut (ml)') ?></td>
                    <td style="width:10%"><?= Yii::t('app', 'Amaun Petrol (liter)') ?></td>
                    <td style="width:10%"><?= Yii::t('app', 'Bil. Caj') ?></td>
                    <td style="width:10%"><?= Yii::t('app', 'Bil. Mesin Digunakan') ?></td>
                </tr>
                <tr>
                    <?php 
                        if($model){
                            foreach($dalamluar1 as $dalamluar){
                                echo "<tr>";
                                    echo "<td style='font-weight: bold;'>". (!empty($dalamluar->jenisRacun->PRGN) ? $dalamluar->jenisRacun->PRGN : null) ."</td>";
                                    echo "<td style='font-weight: bold;text-align:right'>". (!empty($dalamluar->AMAUNRACUN) ? number_format($dalamluar->AMAUNRACUN, 2):null) ."</td>";
                                    echo "<td style='font-weight: bold;'>". (!empty($dalamluar->jenisPelarut->PRGN) ? $dalamluar->jenisPelarut->PRGN:null) ."</td>";
                                    echo "<td style='font-weight: bold;text-align:right'>". (!empty($dalamluar->AMAUNPELARUT) ? number_format($dalamluar->AMAUNPELARUT, 2):null) ."</td>";
                                    echo "<td style='font-weight: bold;text-align:right'>". (!empty($dalamluar->AMAUNPETROL) ? number_format($dalamluar->AMAUNPETROL, 2):null) ."</td>";
                                    echo "<td style='font-weight: bold;text-align:right'>". $dalamluar->BILCAJ ."</td>";
                                    echo "<td style='font-weight: bold;text-align:right'>". $dalamluar->BILMESIN ."</td>";
                                echo "</tr>";
                            }	
                        }							
                    ?>        
                </tr>
        </thead>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left;font-size:12px">2. PENGGUNAAN RACUN SERANGGA (DALAM RUMAH)</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
            <tr>
                <tr>
                    <td style="width:30%"><?= Yii::t('app', 'Jenis Racun Serangga') ?></td>
                    <td style="width:20%"><b><?= !empty($racundalam->jenisRacun->PRGN) ? $racundalam->jenisRacun->PRGN : null?></b></td>
                    <td style="width:30%"><?= Yii::t('app', 'Amaun Racun Serangga Digunakan (ml)') ?></td>
                    <td style="text-align:right;width:20%"><b><?= !empty($racundalam->AMAUNRACUN) ? number_format($racundalam->AMAUNRACUN, 2):null?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Jenis Pelarut') ?></td>
                    <td><b><?= !empty($racundalam->jenisPelarut->PRGN) ? $racundalam->jenisPelarut->PRGN:null ?></b></td>
                    <td><?= Yii::t('app', 'Amaun Pelarut (ml)') ?></td>
                    <td style="text-align:right"><b><?= !empty($racundalam->AMAUNPELARUT) ? number_format($racundalam->AMAUNPELARUT,2):null?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Bil. Caj') ?></td>
                    <td style="text-align:right"><b><?= !empty($racundalam->BILCAJ) ? $racundalam->BILCAJ:null ?></b></td>
                    <td><?= Yii::t('app', 'Amaun Petrol (liter)') ?></td>
                    <td style="text-align:right"><b><?= !empty($racundalam->AMAUNPETROL) ? $racundalam->AMAUNPETROL : null ?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Bil. Mesin Digunakan') ?></td>
                    <td style="text-align:right"><b><?= !empty($racundalam->BILMESIN) ? $racundalam->BILMESIN : null?></b></td>
                </tr>
            </tr>
        </thead>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left;font-size:12px">3. PENGGUNAAN RACUN SERANGGA (LUAR RUMAH)</h6>
        <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
            <tr>
                <tr>
                    <td style="width:30%"><?= Yii::t('app', 'Jenis Racun Serangga') ?></td>
                    <td style="width:20%"><b><?= !empty($racunluar->jenisRacun->PRGN) ? $racunluar->jenisRacun->PRGN : null?></b></td>
                    <td style="width:30%"><?= Yii::t('app', 'Amaun Racun Serangga Digunakan (ml)') ?></td>
                    <td style="text-align:right;width:20%"><b><?= !empty($racunluar->AMAUNRACUN) ? number_format($racunluar->AMAUNRACUN,2):null?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Jenis Pelarut') ?></td>
                    <td><b><?= !empty($racunluar->jenisPelarut->PRGN) ? $racunluar->jenisPelarut->PRGN:null ?></b></td>
                    <td><?= Yii::t('app', 'Amaun Pelarut (ml)') ?></td>
                    <td style="text-align:right"><b><?= !empty($racunluar->AMAUNPELARUT) ? number_format($racunluar->AMAUNPELARUT,2):null ?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Bil. Caj') ?></td>
                    <td style="text-align:right"><b><?= !empty($racunluar->BILCAJ) ? $racunluar->BILCAJ:null ?></b></td>
                    <td><?= Yii::t('app', 'Amaun Petrol (liter)') ?></td>
                    <td style="text-align:right"><b><?= !empty($racunluar->AMAUNPETROL) ? number_format($racunluar->AMAUNPETROL,2): null ?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Bil. Mesin Digunakan') ?></td>
                    <td style="text-align:right"><b><?= !empty($racunluar->BILMESIN) ? $racunluar->BILMESIN:null ?></b></td>
                </tr>
            </tr>
        </thead>
    </table>
    <br>
    <p style="page-break-after: auto"></p>
    <table class="sampel-table" style="width:100%">
        <tr style="border-style:none;"><td style="padding-left:0px"><h6 class="document-title" style="text-align:left">H. NAMA AHLI PASUKAN</h6></td></tr>
        <thead style="color:#000000">
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
        </thead>
    </table>

</div>








