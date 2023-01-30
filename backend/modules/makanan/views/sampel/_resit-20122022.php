<?php

use common\models\Pengguna;
use common\utilities\DateTimeHelper;
use backend\modules\makanan\models\SampelMakanan;
use backend\modules\lawatanmain\models\LawatanMain;
/**
 * @var SampelMakanan $sampel
 */

$alphabet = [
    'a', 'b', 'c', 'd', 'e',
    'f', 'g', 'h', 'i', 'j',
    'k', 'l', 'm', 'n', 'o',
    'p', 'q', 'r', 's', 't',
    'u', 'v', 'w', 'x', 'y',
    'z'
];


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

$dataProvider->setPagination(false);
$models = $dataProvider->getModels();
foreach ($models as $sampel) {
    $rows[] = [
        $sampel->NOSAMPEL, 
        $sampel->TRKHSAMPEL,
        $sampel->JENIS_SAMPEL,
        $sampel->ID_JENISANALISIS1,
        $sampel->ID_JENISANALISIS2,
        $sampel->ID_JENISANALISIS3,
        $sampel->JENAMA,
        $sampel->HARGA,
        $sampel->PEMBEKAL,
        $sampel->CATATAN,
    ];
}
    
$sampel1 = SampelMakanan::findAll(['NOSIRI' => $sampel->NOSIRI]);  

$pgndaftar = $sampel->PGNDAFTAR;
$get_data = Yii::$app->db->createCommand(" SELECT DESIGNATION FROM MAJLIS.PRUSER
            WHERE USERID=$pgndaftar")->queryOne();
$jawatan = implode('', $get_data);  

$main = $sampel->main;


// $model = LawatanMain::findAll(['NOSIRI' => $sampel->NOSIRI]);  
// foreach ($model as $main) {
//     $rows[] = [
//         $main->NAMAPENERIMA, 
//         $main->NOKPPENERIMA,
//     ];
// }

$sum = Yii::$app->db->createCommand("SELECT SUM(HARGA) FROM TBSAMPEL_SM
        WHERE NOSIRI = '$sampel->NOSIRI'
        FETCH FIRST 3 ROWS ONLY")->queryOne();
$sum1 = implode('', $sum);  
$newWord   = numberTowords($sum1);

// var_dump($sum);
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
        font-size: 14px;
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
        height: 32px;
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

<div class="centerImage">
    <img height="100" src="<?= Yii::getAlias('@web/images/logo.png') ?>" />
</div>

<h6 class="document-title" style="font-size: 20px;">JABATAN KESIHATAN PERSEKITARAN</h6>
<h6 class="document-title" style="font-size: 20px;">MAJLIS BANDARAYA PETALING JAYA</h6>
<br>
<h6 class="document-title" style="font-size: 20px;">RESIT PEMBELIAN</h6>
<br>
<div style="padding-top:15px;">
    <table class="content-table">
        <tr style="padding-top:15px;">
            <td>
                <div style="padding-top:15px;">Diterima daripada Penolong Pegawai Kesihatan Persekitaran (PPKP) <b><?=$sampel->createdByUser->NAMA?></b></div> 
                <!-- <b>Sebanyak Ringgit Malaysia : </b><b><?=$newWord?></b> Sahaja <b>(RM<?=$sum1?>)
                </b> pada (Tarikh) <b><?=date('d-m-Y', strtotime($sampel->TRKHSAMPEL)) ?></b> Sebagai bayaran untuk :</div> -->
            </td>
        </tr> 
        <tr style="padding-top:15px;">
            <td>
                <div>Sebanyak Ringgit Malaysia : <b><?=$newWord?> SAHAJA</b><b> (RM<?=$sum1?>)</div>
            </td>
        </tr> 
        <tr style="padding-top:15px;">
            <td>
                <div>pada (Tarikh) <b><?=date('d-m-Y', strtotime($sampel->TRKHSAMPEL)) ?></b> Sebagai bayaran untuk :</div>
            </td>
        </tr> 
    </table>
    <br> 
    <table class="sampel-table" style="width:250px;">
        <tr>
            <td style="text-align:center;font-size: 13px;width:10px;"><b>Bil.</b></td>
            <td style="text-align:center;width:200px"><b>No. Sampel</b></td>
            <td style="text-align:center;width:200px"><b>Jenis Makanan</b></td>
            <td style="text-align:center;width:200px"><b>Harga</b></td>
        </tr>  
        <tr>
            <?php 
                if($sampel){
                    $i=0;
                    foreach($sampel1 as $model1){
                        echo "<tr>";
                            $i = $i+1;

                            echo "<td>". $i ."</td>";
                            echo "<td>". $model1->NOSAMPEL ."</td>";
                            echo "<td>". $model1->JENIS_SAMPEL ."</td>";
                            echo "<td style='text-align:right'>". $model1->HARGA ."</td>";
                        echo "</tr>";
                    }	
                }	
            ?>        
        </tr> 
        <tr>
            <td></td>
            <td></td>
            <td style="text-align:right;height:60px;"><b>Jumlah (RM): </b></td>
            <td style="text-align:right;height:60px;"><b><?=$sum1?></b></td>
        </tr>   
    </table>
    <br>
    <br>
    <table class="content-table">
        <tr style="padding-top:25px;">
            <td>
                <div style="padding-top:15px;">Nama Pengusaha &nbsp;&nbsp;&nbsp;&nbsp;: <b><?=$main->NAMAPENERIMA?></b></div> 
            </td>
        </tr> 
        <br>
        <tr style="padding-top:15px;">
            <td>
                <div style="padding-top:15px;">No. Kad Pengenalan   : <b><?=$main->NOKPPENERIMA?></b></div> 
            </td>
        </tr> 
        <br>
        <tr style="padding-top:15px;">
            <td>
                <div style="padding-top:15px;">Tandatangan   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:___________________________________________</b></div> 
            </td>
        </tr>
        <br> 
        <tr style="padding-top:15px;">
            <td>
                <div style="padding-top:15px;">Cop Premis   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:___________________________________________</b></div> 
            </td>
        </tr> 
    </table>
</div>  







