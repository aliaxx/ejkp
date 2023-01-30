<?php

use common\models\Pengguna;
use common\utilities\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\modules\makanan\models\SampelMakanan;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\lawatanmain\models\LawatanPasukan;
use backend\modules\vektor\models\SasaranUlv;
use backend\modules\vektor\models\RacunSrt;
use backend\modules\vektor\utilities\OptionHandler;

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

$sasarans = $model->sasaranulv;
// $sasaran1 = Yii::$app->db->createCommand("SELECT SUM(SASARAN1) FROM TBSASARAN_ULV WHERE NOSIRI = '$model->NOSIRI'")->queryScalar();
// $pencapaian1 = Yii::$app->db->createCommand("SELECT SUM(PENCAPAIAN1) FROM TBSASARAN_ULV WHERE NOSIRI = '$model->NOSIRI'")->queryScalar();
// $sasaran2 = Yii::$app->db->createCommand("SELECT SUM(SASARAN2) FROM TBSASARAN_ULV WHERE NOSIRI = '$model->NOSIRI'")->queryScalar();
// $pencapaian2 = Yii::$app->db->createCommand("SELECT SUM(PENCAPAIAN2) FROM TBSASARAN_ULV WHERE NOSIRI = '$model->NOSIRI'")->queryScalar();
// $jumpremis = implode('', $jum); 
// $jumpencapaian = $gredpremis->markahpremis['sum'];
// $jumsasaran = $gredpremis->markahpremis['gred'];


$ketua = LawatanPasukan::findOne(['NOSIRI' => $model->NOSIRI, 'JENISPENGGUNA' => 1]);  
$idketua = $ketua->IDPENGGUNA;
$nama = Yii::$app->db->createCommand(" SELECT NAME FROM C##MAJLIS.PRUSER
            WHERE USERID=$idketua")->queryScalar();
$jawatan = Yii::$app->db->createCommand(" SELECT DESIGNATION FROM C##MAJLIS.PRUSER
            WHERE USERID=$idketua")->queryScalar();

$ahlis = Yii::$app->db->createCommand("SELECT COUNT(IDPENGGUNA) FROM TBLAWATAN_PASUKAN WHERE NOSIRI ='$model->NOSIRI' AND JENISPENGGUNA='2'")->queryScalar();
$model->ULV_MASAMULAHUJAN = date('H:i A', strtotime($model->ULV_MASAMULAHUJAN));
$model->ULV_MASATAMATHUJAN = date('H:i A', strtotime($model->ULV_MASATAMATHUJAN));
$model->ULV_MASAMULAANGIN = date('H:i A', strtotime($model->ULV_MASAMULAANGIN));
$model->ULV_MASATAMATANGIN = date('H:i A', strtotime($model->ULV_MASATAMATANGIN));
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
        height: 35px;
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
                <h6 class="document-title">LAPORAN AKTIVITI SEMBURAN ULV/TIFA (BORANG ULV)</h6>
                <h6 class="document-title">UNIT KAWALAN VEKTOR</h6>
                <h6 class="document-title">JABATAN PERKHIDMATAN KESIHATAN & PERSEKITARAN</h6>
                <h6 class="document-title">MAJLIS BANDARAYA PETALING JAYA</h6>
            </div>
        </td>
    </tr>
</table>
<br>
<div>
    <table class="sampel-table">
        <tr>
            <td colspan="3" style="border-style:none;padding-left: 0px;"><h6 class="document-title" style="text-align:left;">A. MAKLUMAT KES</h6></td>      
            <td colspan="2" style="border-style:none;padding-left: 0px;"><h6 class="document-title" style="text-align:left">B. MAKLUMAT AKTIVITI</h6></td>
        </tr>
        <tr style="border-style:none;padding-top:5px"></tr>
        <tr>
            <td style="width:130px"><?= Yii::t('app', 'NAMA KES RUJUK') ?></td>
            <td style="width:200px"><b><?=$model->V_RUJUKANKES?></b></td>
            <td style="border-style:none;width:5px"></td>      
            <td style="width:130px"><?= Yii::t('app', 'TARIKH ONSET') ?></td>
            <td style="width:200px"><b><?=date('d-m-Y', strtotime($model->ULV_TRKHONSET))?></b></td>
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
            <td><?= Yii::t('app', 'TARIKH/MASA MULA AKTIVITI') ?></td>
            <td><b><?=date('d-m-Y H:i A', strtotime($model->TRKHMULA))?></b></td>
        </tr>
        <tr>
            <td><?= Yii::t('app', 'NEGERI') ?></td>
            <td><b>Selangor</b></td>
            <td style="border-style:none;width:5px"></td>      
            <td><?= Yii::t('app', 'TARIKH/MASA AKHIR AKTIVITI') ?></td>
            <td><b><?=date('d-m-Y H:i A', strtotime($model->TRKHTAMAT))?></b></td>
        </tr>
        <tr>
            <td><?= Yii::t('app', 'DAERAH') ?></td>
            <td><b>Petaling</b></td>
            <td style="border-style:none;width:5px"></td>      
            <td><?= Yii::t('app', 'TARIKH NOTIFIKASI KES') ?></td>
            <td><b><?=date('d-m-Y H:i A', strtotime($model->V_TRKHNOTIFIKASI))?></b></td>
        </tr>
        <tr>
            <td><?= Yii::t('app', 'MUKIM') ?></td>
            <td><b><?=isset($model->mukim->PRGN) ? $model->mukim->PRGN:null ?></b></td>
            <td style="border-style:none;width:5px"></td>      
            <td><?= Yii::t('app', 'NAMA PBT') ?></td>
            <td><b>MBPJ</b></td>                    
        </tr>
        <tr>
            <td><?= Yii::t('app', 'LOKALITI') ?></td>
            <td><b><?=isset($model->lokaliti->PRGN) ? $model->lokaliti->PRGN:null ?></b></td>
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
        <!-- <tr style="border-style:none">
            <td style="border-style:none"></td>
            <td style="border-style:none"></td>
        </tr> -->
        <!-- <tr style="border-style:none">
            <td style="border-style:none"></td>
            <td style="border-style:none"></td>
        </tr> -->
    </table>
    <br>
    <h6 class="document-title" style="text-align:left">C. JENIS SEMBURAN</h6>
    <div style="margin-left:30px;font-size:13px">JENIS SEMBURAN &nbsp; : <b><?= OptionHandler::resolve('jenis-sembur', $model->V_JENISSEMBUR) ?></b></div>
    <br>
    <?php
        if($model->V_JENISSEMBUR == 1){
            echo "<div style='margin-left:30px;font-size:13px'>". "KATEGORI LOKALITI ". "&nbsp;: " . "<b>" . OptionHandler::resolve('kat-lokaliti', $model->V_KATLOKALITI) . "</b>" ."</div>";
            echo "<br>";
            echo "<div style='margin-left:30px;font-size:13px'>". "PUSINGAN &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: "."<b>" .$model->V_PUSINGAN. "</b>" ."</div>";
        }else{
            echo "<div style='margin-left:30px;font-size:13px'>". "PENCEGAHAN &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ". "<b>" .OptionHandler::resolve('ulv-surveilan', $model->V_ID_SUREVEILAN). "</b>" ."</div>";
        }
    ?>
    <br>
    <h6 class="document-title" style="text-align:left">D. CUACA</h6>
    <table>
        <tr>
            <td style="text-align:left;">
                <h6 class="document-title" style="text-align:left;">1. HUJAN</h6>
                <table class="sampel-table" style="margin-top:10px">
                    <tr>
                        <td style="width:110px"><?= Yii::t('app', 'KEADAAN HUJAN') ?></td>
                        <td style="width:220px"><b><?= OptionHandler::resolve('keadaan-hujan', $model->ULV_KEADAANHUJAN) ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'MASA DARI') ?></td>
                        <td><b><?=!empty($model->ULV_MASAMULAHUJAN)? $model->ULV_MASAMULAHUJAN:null?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'MASA SEHINGGA') ?></td>
                        <td><b><?=!empty($model->ULV_MASATAMATHUJAN)? $model->ULV_MASATAMATHUJAN:null?></b></td>
                    </tr>
                </table>
            </td>
            <td style="width:10px"></td>
            <td>
                <h6 class="document-title" style="text-align:left">2. ANGIN</h6>
                <table class="sampel-table" style="margin-top:10px">
                    <tr>
                        <td style="width:110px"><?= Yii::t('app', 'KEADAAN ANGIN') ?></td>
                        <td style="width:220px"><b><?= OptionHandler::resolve('keadaan-angin', $model->ULV_KEADAANANGIN) ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'MASA DARI') ?></td>
                        <td><b><?=!empty($model->ULV_MASAMULAANGIN)? $model->ULV_MASAMULAANGIN:null?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'MASA SEHINGGA') ?></td>
                        <td><b><?=!empty($model->ULV_MASATAMATANGIN)? $model->ULV_MASATAMATANGIN:null?></b></td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <br>
    <!-- <p style="page-break-after: always"></p> -->
    <h6 class="document-title" style="text-align:left">E. MAKLUMAT ULV</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
            <tr>
                <tr>
                    <td><?= Yii::t('app', 'JENIS MESIN ULV') ?></td>
                    <td style="width:200px"><b><?=$model->ULV_JENISMESIN?></b></td>
                    <td><?= Yii::t('app', 'BIL. MESIN DIGUNAKAN') ?></td>
                    <td style="text-align:right;width:50px"><b><?=$model->ULV_BILMESIN?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'JENIS RACUN SERANGGA') ?></td>
                    <td><b><?=isset($model->racun->PRGN) ? $model->racun->PRGN:null ?></b></td>
                    <td><?= Yii::t('app', 'AMAUN RACUN SERANGGA (g/ml)') ?></td>
                    <td style="text-align:right"><b><?=$model->ULV_AMAUNRACUN?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'JENIS PELARUT') ?></td>
                    <td><b><?=isset($model->pelarut->PRGN) ? $model->pelarut->PRGN:null ?></b></td>
                    <td><?= Yii::t('app', 'AMAUN PELARUT (liter)') ?></td>
                    <td style="text-align:right"><b><?=$model->ULV_AMAUNPELARUT?></b></td>
                </tr>
                <tr>
                    <td style="border-style:none"></td>
                    <td style="border-style:none"></td>
                    <td><?= Yii::t('app', 'AMAUN PETROL (liter)') ?></td>
                    <td style="text-align:right"><b><?=$model->ULV_AMAUNPETROL?></b></td>
                </tr>
            </tr>
        </thead>
    </table>
    <br>
    <p style="page-break-after: always"></p>
    <h6 class="document-title" style="text-align:left">F. LIPUTAN SEMBURAN</h6>
    <table class="sampel-table">
        <thead>
            <tr>
                <td rowspan="3" style="font-size:14px;text-align:center;width:150px">JENIS PREMIS</td>
                <td colspan="4" style="font-size:14px;text-align:center">LINKUNGAN JARAK DARI PREMIS KES<br>SEKIRANYA OPERASI KAWALAN KES/WABAK</td>
                <td colspan="3" style="font-size:14px;text-align:center">JUMLAH/KESELURUHAN</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:14px;text-align:center">DALAM LINKUNGAN 400M</td>
                <td colspan="2" style="font-size:14px;text-align:center">> 400 M</td>
                <td rowspan="2" style="font-size:14px;text-align:center">SASARAN BILANGAN PREMIS</td>
                <td rowspan="2" style="font-size:14px;text-align:center">PENCAPAIAN BILANGAN PREMIS</td>
                <td rowspan="2" style="font-size:14px;text-align:center">PENCAPAIAN (%)</td>
            </tr>
            <tr>
                <td style="font-size:14px;">SASARAN BILANGAN PREMIS</td>
                <td style="font-size:14px;">PENCAPAIAN BILANGAN PREMIS DISEMBUR</td>
                <td style="font-size:14px;">SASARAN BILANGAN PREMIS</td>
                <td style="font-size:14px;">PENCAPAIAN BILANGAN PREMIS DISEMBUR</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php 
                    if($model){
                        foreach($sasarans as $sasaran){
                            echo "<tr>";
                            echo "<td style='font-size:14px;font-weight: bold;'>". $sasaran->premis1->PRGN ."</td>";
                            echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->SASARAN1 ."</td>";
                            echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->PENCAPAIAN1 ."</td>";
                            echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->SASARAN2 ."</td>";
                            echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->PENCAPAIAN2 ."</td>";
                            // echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran ."</td>";
                            echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->jumSasaran['sasaran'] ."</td>";
                            echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->jumSasaran['pencapaian'] ."</td>";
                            echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". " " ."</td>";
                            echo "</tr>";
                        }	
                    }							
                ?>        
            </tr>
        </tbody>
        <tr class="sampel-table">
            <td style="font-size:14px;"><b><?= Yii::t('app', 'JUMLAH PREMIS') ?></b></td>
            <?php 
                if($sasarans){
                    echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->jumSasaran['sasaran1'] ."</td>";
                    echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->jumSasaran['pencapaian1'] ."</td>";
                    echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->jumSasaran['sasaran2'] ."</td>";
                    echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->jumSasaran['pencapaian2'] ."</td>";
                    echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->jumSasaran['sumsasaran'] ."</td>";
                    echo "<td style='font-size:14px;font-weight: bold;text-align:right'>". $sasaran->jumSasaran['sumpencapaian'] ."</td>";
                }
            ?>
            <td style="text-align:right"><b></b></td>
        </tr>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left">G. NAMA AHLI PASUKAN</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
                <tr>
                    <td style="width:60%"><?= Yii::t('app', 'NAMA ANGGOTA') ?></td>
                    <td style="width:40%"><?= Yii::t('app', 'JAWATAN') ?></td>
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
    <br>
    <h6 class="document-title" style="text-align:left">H. PENYEDIA LAPORAN</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
                <tr>
                    <td style="width:60%"><?= Yii::t('app', 'LAPORAN DISEDIAKAN OLEH') ?></td>
                    <td style="width:40%"><?= Yii::t('app', 'JAWATAN') ?></td>
                </tr>
                <tr>
                    <td style="width:60%"><b><?= $nama ?></b></td>
                    <td style="width:40%"><b><?= $jawatan?></b></td>
                </tr>
        </thead>
    </table>
</div>








