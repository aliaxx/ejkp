<?php

use common\models\Pengguna;
use common\utilities\DateTimeHelper;
// use backend\modules\makanan\models\BarangSitaan;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\makanan\utilities\OptionHandler;
/**
 * @var SampelHandswab $model
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

$dataProvider->setPagination(false);
$models = $dataProvider->getModels();
foreach ($models as $model) {
    $rows[] = [
        $model->IDSAMPEL, 
        $model->NAMAPEKERJA,
        $model->NOKP,
        $model->JENIS,
        // $model->TY2,
        OptionHandler::resolve('ty2-fhc', $model->TY2),
        $model->FHC,
        $model->KEPUTUSAN,
        $model->CATATAN,
        $pgndaftar = $model->PGNDAFTAR,
    ];
}

// var_dump(OptionHandler::resolve('ty2-fhc', $model->TY2));
// exit();

    
// $sampel1 = SampelMakanan::findAll(['NOSIRI' => $model->NOSIRI]);  

// $pgndaftar = $model->PGNDAFTAR;
$get_data = Yii::$app->db->createCommand(" SELECT NVL(DESIGNATION, NULL) FROM C##MAJLIS.PRUSER
            WHERE USERID=$pgndaftar")->queryOne();
$jawatan = implode('', $get_data);  

$positif = Yii::$app->db->createCommand("SELECT COUNT(KEPUTUSAN) FROM TBSAMPEL_HS WHERE NOSIRI ='$model->NOSIRI' AND KEPUTUSAN='1'")->queryOne();
$positif1 = implode('', $positif);  

$positif_male = Yii::$app->db->createCommand("SELECT COUNT(KEPUTUSAN) FROM TBSAMPEL_HS WHERE NOSIRI ='$model->NOSIRI' AND KEPUTUSAN='1'
AND JENIS='1'")->queryOne();
$positif_male1 = implode('', $positif_male);  

$positif_female = Yii::$app->db->createCommand("SELECT COUNT(KEPUTUSAN) FROM TBSAMPEL_HS WHERE NOSIRI ='$model->NOSIRI' AND KEPUTUSAN='1'
AND JENIS='2'")->queryOne();
$positif_female1 = implode('', $positif_female);  

// $nolesen = $model->lesen->NOLESEN;

// if(!empty($model->lesen->NOLESEN)){
//     $nolesen = $model->lesen->NOLESEN;
// }else{
//     $nolesen = $model->lesen->NOPETAK;
// }

// var_dump($nolesen);
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

    .model-table td{
        margin: auto;
        border-collapse: separate;
        border: 1px solid black;
        width: 350px;
        height: 20px;
        /* font-size: 20px; */
        padding-left: 10px;
        padding-right: 10px;
    }

    .centerImage
    {
        text-align:center;
        display:block;
    }

    .sampel-table td{
        margin: auto;
        border-collapse: separate;
        border: 1px solid black;
        /* width: 350px; */
        height: 50px;
        font-size: 13px;
        padding-left: 5px;
        padding-right: 5px;
    }

    .positif-table td{
        margin: auto;
        border-collapse: separate;
        border: 1px solid black;
        /* width: 350px; */
        height: 10px;
        font-size: 12px;
        padding-left: 5px;
        padding-right: 5px;
        padding-top: 7px;
    }

</style>

<table>
    <tr>
        <td></td>
        <td style="width:290px"></td>
        <td style="width:235px"></td>
        <td style="text-align:right;font-size:16px">
            <div style=" font-size: 13px;"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
            No. Ruj &nbsp; &nbsp;&nbsp;: (<?= $model->NOSIRI ?>)MBPJ/060200/T/K9/UKMM/SWAB</div>
        </td>
    </tr>
</table>
<br>
<table>
    <tr>
        <td></td>
        <td style="width:400px"></td>
        <td style="text-align:center;font-size:16px;text-decoration: underline;"><b>BORANG SWAB TANGAN</b></td>
    </tr>
</table>
<br>
<table>
    <tr>
        <td>
            <div style="text-align:left">
                <div><img height="100" src="<?= Yii::getAlias('@web/images/logo.png') ?>" /></div>
            </div>
        </td>
        <td style="width: 10px;">&nbsp;</td>
        <td style="text-align:left;font-size:14px;width:330px;">
            <div>
                <div><b>UNIT KAWALAN MUTU MAKANAN</b></div>
                <div><b>JABATAN PERKHIDMATAN KESIHATAN DAN PERSEKITARAN</b></div>
                <div><b>MAJLIS BANDARAYA PETALING JAYA</b></div>
            </div>
        </td>
        <td style="width: 30px;">&nbsp;</td>
        <td style="text-align:left;font-size:16px;width:330px;">
            <div>
                <div>Nama Premis &nbsp;  : <b><?= $model->lesen->NAMAPREMIS ?></b></div>
                <div>Alamat Premis : <b><?= $model->main->PRGNLOKASI ?></b></div>
                <div>Jenis Jualan &nbsp; &nbsp; &nbsp;: <b><?= $model->lesen->JENIS_JUALAN ?><b></div>
            </div>
        </td>
        <td style="width: 30px;">&nbsp;</td>
        <td style="text-align:left;font-size:16px;width:330px;">
            <div>
                <div>No. Lesen &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: <b><?= (!empty($model->lesen->NOLESEN))? $model->lesen->NOLESEN: null ?></b></div>
                <div>No. Petak &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;: <b><?= (!empty($model->lesen->NOPETAK))? $model->lesen->NOPETAK: null ?></b></div>
                <div>Tarikh/Masa&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; : <b><?=date('Y-m-d', $model->main->TRKHDAFTAR)?>/<?=date('H:i A', $model->main->TRKHDAFTAR)?></b></div>
                <div>Kategori Lesen&nbsp;&nbsp;&nbsp;: <b><?= $model->lesen->KATEGORI_LESEN ?></b></div>
            </div>
        </td>
    </tr>
</table>
<br>
<table class="sampel-table" Style="margin-top:30px;margin-left:10px;margin-bottom:20px">
    <tr>
        <td style="font-size: 13px;width:50px;text-align:center"><b>Bil.</b></td>
        <td style="font-size: 13px;text-align:center"><b>Id Sampel</b></td>
        <td style="font-size: 13px;width:250px;text-align:center"><b>Nama Pekerja</b></td>
        <td style="font-size: 13px;width:100px;text-align:center"><b>No. K/P @ Passport</b></td>
        <td style="font-size: 13px;width:100px;text-align:center"><b>Jantina</b></td>
        <td style="font-size: 13px;width:100px;text-align:center"><b>TY2</b></td>
        <td style="font-size: 13px;width:100px;text-align:center"><b>FHC</b></td>
        <td style="font-size: 13px;width:100px;text-align:center"><b>Catatan</b></td>
        <td style="font-size: 13px;width:140px;text-align:center"><b>Keputusan (Kosong = Negatif)</b></td>
    </tr>  
    <tr>
        <?php 
            if($model){
                $i=0;
                foreach($model->pekerja as $model1){
                    echo "<tr>";
                    $i = $i+1;            

                    echo "<td>". $i ."</td>";
                    echo "<td>". $model1->IDSAMPEL ."</td>";
                    echo "<td>". $model1->NAMAPEKERJA ."</td>";
                    echo "<td>". $model1->NOKP ."</td>";
                    echo "<td>". OptionHandler::resolve('jantina', $model1->JENIS) ."</td>";
                    echo "<td>". OptionHandler::resolve('ty2-fhc', $model1->TY2) ."</td>";
                    echo "<td>". OptionHandler::resolve('ty2-fhc', $model1->FHC) ."</td>";
                    echo "<td>". $model1->CATATAN ."</td>";
                    echo "<td>". OptionHandler::resolve('keputusan', $model1->KEPUTUSAN) ."</td>";
                    echo "</tr>";
                }	
            }							
        ?>        
    </tr>
</table>
<br>
<table class="content-table">
    <tr style="padding-top:13px;">
        <td>    
            <div><b>Dianalisa Oleh : </b></div>
            <br>
            <br>
            <div>.........................................................</div>
            <div>Nama : <?= $model->main->createdByUser->NAMA ?></div>
            <div>Jawatan : <?= $jawatan ?></div>
        </td>
        <td style="width: 100px; ">&nbsp;</td>
        <td>
            <div style=" font-size: 13px;text-align:right;">
                <div><b>Disahkan Oleh : </b></div>
                <br>
            <br>
            <div>.......................................................</div>
            <div>Nama : </div>
            <div>No. K/P : </div>
            </div>
        </td>
        <td style="width: 100px; ">&nbsp;</td>
        <td>
            <!-- <table class="positif-table">
                <tr>
                    <td style="font-size: 12px;width:100px;">Positif</td>
                    <td style="font-size: 12px;width:50px;"><?=$positif1?></td>
                </tr>  
                <tr>
                    <td style="font-size: 12px;width:100px;">Lelaki</td>
                    <td style="font-size: 12px;width:50px;"><?=$positif_male1?></td>
                </tr>  
                <tr>
                    <td style="font-size: 12px;width:100px;">Perempuan</td>
                    <td style="font-size: 12px;width:50px;"><?=$positif_female1?></td>
                </tr>  
            </table> -->
        </td>
        <td style="width: 100px; ">&nbsp;</td>
        <td>
            <!-- <table class="positif-table">
                <tr>
                    <td style="font-size: 12px;width:100px;">Jum. Sblm</td>
                    <td style="font-size: 12px;width:50px;"></td>
                </tr>  
                <tr>
                    <td style="font-size: 12px;width:100px;">Jum</td>
                    <td style="font-size: 12px;width:50px;"></td>
                </tr>  
                <tr>
                    <td style="font-size: 12px;width:100px;">Jum. Besar</td>
                    <td style="font-size: 12px;width:50px;"></td>
                </tr>  
            </table> -->
        </td>
    </tr>  
</table>
</div>  









