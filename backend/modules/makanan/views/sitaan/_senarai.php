<?php

use common\models\Pengguna;
use common\utilities\DateTimeHelper;
use backend\modules\makanan\models\BarangSitaan;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPemilik;
/**
 * @var BarangSitaan $model
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
        $model->JENISMAKANAN, 
        $model->PENGENALAN,
        $model->KUANTITI,
        $model->HARGA,
        $model->KESALAHAN,
        $model->NAMAPEMBUAT,
        $model->ALAMATPEMBUAT,
        $model->CATATAN,
    ];
}
    
// $sampel1 = SampelMakanan::findAll(['NOSIRI' => $model->NOSIRI]);  

$pgndaftar = $model->PGNDAFTAR;
$get_data = Yii::$app->db->createCommand(" SELECT DESIGNATION FROM MAJLIS.PRUSER
            WHERE USERID=$pgndaftar")->queryOne();
$jawatan = implode('', $get_data);  

$sum = Yii::$app->db->createCommand("SELECT SUM(HARGA) FROM TBSITAAN
        WHERE NOSIRI = '$model->NOSIRI'")->queryOne();
$sum1 = implode('', $sum);  

// $epoch=date('Y-m-d H:i A', $model2->TRKHAKHIR); //convert timestamps to datetime -NOR03112022
// echo ;
// $model2->TRKHAKHIR = DateTimeHelper::convert($model2->TRKHAKHIR, true);


// var_dump($sum1);
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

</style>

<table>
    <tr>
        <td></td>
        <td style="width:350px"></td>
        <td style="width:150px"></td>
        <td style="text-align:right;font-size:16px">
            <div style=" font-size: 12px;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
            No. Ruj &nbsp; &nbsp;&nbsp;: (<?= $model->NOSIRI ?>) dlm MBPJ/060200/T/P48/UKMM/RMPS</div>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td style="width:150px"></td>
        <td style="width:100px"></td>
        <td style="text-align:center;font-size:16px">
            <div>
                <div class="centerImage">
                    <div><img height="100" src="<?= Yii::getAlias('@web/images/logo.png') ?>" /></div>
                </div>
                <div><b>JABATAN PERKHIDMATAN KESIHATAN DAN PERSEKITARAN</b></div>
                <div><b>MAJLIS BANDARAYA PETALING JAYA</b></div>
            </div>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td style="padding-top:40px;width:250px">
            <div>
                <div>Alamat Premis : <b><?= $model->main->PRGNLOKASI ?></b></div>
                <!-- <div>_________________________________________</div> -->
                <!-- <div>_________________________________________</div> -->
            </div>
        </td>
        <td style="padding-top:30px;width:100px">
        <td style="padding-top:30px;text-align:center">
            <div><b>SENARAI MAKANAN YANG DI LAK/SITA</b></div>
            <div>------------------------------------------------------------</div>
        </td>
        <td style="padding-top:50px;width:200px">
        <td style="padding-top:40px;text-align:right">
            <div>Tarikh : <b><?=date('Y-m-d', $model->main->TRKHDAFTAR)?></b></div>
        </td>
    </tr>
</table>
<table class="sampel-table" Style="margin-top:25px;margin-left:10px;margin-bottom:20px">
    <tr>
        <td style="font-size: 13px;width:50px;"><b>Bil.</b></td>
        <td style="font-size: 13px;width:150px;"><b>Jenis Makanan/ Perkakas</b></td>
        <td style="font-size: 13px;"><b>Tanda Pengenalan</b></td>
        <td style="font-size: 13px;"><b>Kuantiti</b></td>
        <td style="font-size: 13px;width:200px;"><b>Nama & Alamat Pembuat/ Pembungkus/Pengimport</b></td>
        <td style="font-size: 13px;width:150px;"><b>kesalahan</b></td>
        <td style="font-size: 13px;"><b>Harga(RM)</b></td>
        <td style="font-size: 13px;width:200px;"><b>Catatan</b></td>
    </tr>  
    <tr>
        <?php 
            if($model){
                $i=0;
                foreach($model->makanan as $model1){
                    echo "<tr>";
                    $i = $i+1;

                    echo "<td>". $i ."</td>";
                    echo "<td>". $model1->JENISMAKANAN ."</td>";
                    echo "<td>". $model1->PENGENALAN ."</td>";
                    echo "<td style='text-align:right;'>". $model1->KUANTITI ."</td>";
                    echo "<td>". $model1->NAMAPEMBUAT . $model1->ALAMATPEMBUAT ."</td>";
                    echo "<td>". $model1->KESALAHAN ."</td>";
                    echo "<td style='text-align:right;'>". $model1->HARGA ."</td>";
                    echo "<td>". $model1->CATATAN ."</td>";
                    echo "</tr>";
                }	
            }							
        ?>        
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <!-- <td></td> -->
        <td style="padding-top:10px;font-size:13px"><b>Jumlah (RM): </b></td>
        <td style="text-align:right;padding-top:10px;font-size:13px"><b><?=$sum1?></b></td>
    </tr>     
</table>
<br>
<!-- <br>
<br> -->
<table class="content-table">
    <tr style="padding-top:10px;">
        <td>    
            <div>Tandatangan Pegawai Berkuasa : </div>
            <br>
            <br>
            <div>.........................................................</div>
            <div>Nama : <b><?= $model->main->createdByUser->NAMA ?></b></div>
            <div>Jawatan : <b><?= $jawatan ?></b></div>
        </td>
        <td style="width: 200px; ">&nbsp;</td>
        <td>
            <div style=" font-size: 13px;text-align:right;">
                <div>Tandatangan (Pemilik makanan) : </div>
                <br>
            <br>
            <div>.......................................................</div>
            <div>Nama : <b><?= $model->main->NAMAPENERIMA ?></b></div>
            <div>No. K/P : <b><?= $model->main->NOKPPENERIMA ?></b></div>
            </div>
        </td>
    </tr>  
</table>
</div>  









