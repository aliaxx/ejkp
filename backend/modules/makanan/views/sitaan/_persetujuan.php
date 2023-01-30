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
        height: 32px;
        /* font-size: 20px; */
        padding-left: 10px;
        padding-right: 10px;
    }

    .centerImage
    {
        text-align:center;
        display:block;
    }
</style>

<table>
    <tr>
        <td>
            <div style="text-align:left">
                <div><img height="100" src="<?= Yii::getAlias('@web/images/logo.png') ?>" /></div>
            </div>
        </td>
        <td style="width: 50px; ">&nbsp;</td>
        <td style="text-align:center;font-size:16px">
            <div>
                <div><b>JABATAN PERKHIDMATAN KESIHATAN DAN PERSEKITARAN</b></div>
                <div><b>MAJLIS BANDARAYA PETALING JAYA</b></div>
            </div>
        </td>
    </tr>
</table>
<h6>________________________________________________________________________________________________________________________<h6>
<h6 class="document-title" style="margin-top:5px">AKTA MAKANAN 1983</h6>
<h6 class="document-title">PERSETUJUAN BAGI PEMUSNAHAN DAN PELUPUSAN MAKANANAN</h6>
<h6 class="document-title">DI BAWAH SUBSEKSYEN 4(8)</h6>
<br>
<div style="padding-top:15px;text-align:justify;">
    <table class="content-table">
        <tr class="document-title" style="padding-top:15px;">
            <td>    
                <div style=" font-size: 13px;width: 240px;text-align:left;">No. Ruj &nbsp; &nbsp;&nbsp;: <b>(<?= $model->NOSIRI ?>) dlm MBPJ/060200/T/P48/UKMM/RMPS</b></div>
            </td>
            <br>
        </tr>  
        <tr class="document-title">
            <td>    
                <div style=" font-size: 13px;width: 240px;text-align:left;">Kepada &nbsp; &nbsp;&nbsp;: <b><?= $model->main->NAMAPENERIMA ?></b></div>
            </td>
        </tr> 
        <tr class="document-title">
            <td>    
                <div style=" font-size: 13px;width: 240px;text-align:left;">K/P &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: <b><?= $model->main->NOKPPENERIMA ?></></div>
            </td>
        </tr> 
        <tr class="document-title">
            <td>    
                <div style=" font-size: 13px;width: 240px;text-align:left;">Alamat  &nbsp; &nbsp; &nbsp;: <b><?= $model->main->PRGNLOKASI ?></b></div>
            </td>
        </tr> 
        <tr class="document-title">
            <td>    
                <div style=" font-size: 13px;width: 240px;text-align:left;">No. Lesen : <b><?= (!empty($model->lesen->NOLESEN))? $model->lesen->NOLESEN: null ?></b></div>               
            </td>
        </tr> 
    </table>
    <br> 
    <table class="content-table">
        <tr>
            <td style="padding-top:10px;text-align:justify;font-size: 13px">    
                <div>Bahawa saya <b><?= $model->main->NAMAPENERIMA ?></b></div>
            </td>
        </tr>  
        <tr>
            <td style="text-align:justify;font-size: 13px">    
                <div>No. Kad Pengenalan <b> <?= $model->main->NOKPPENERIMA ?></b> yang beralamat <b><?= $model->main->PRGNLOKASI ?></div>
            </td>
        </tr>  
        <tr>
            <td style="width:670px;font-size: 13px">    
                <div>pemilik makanan yang telah disita/dilak melalui notis <b>(<?= $model->NOSIRI ?>) dlm MBPJ/060200/T/P48/UKMM/RMPS</b></div>
            </td>
        </tr> 
        <tr>
            <td style="font-size: 13px">    
                <div>bertarikh <b><?=date('Y-m-d', $model->main->TRKHDAFTAR)?></b> dengan ini bersetuju makanan tersebut, seperti butiran seperti di <b>LAMPIRAN A</b></div>
            </td>
        </tr> 
        <tr>
            <td style="font-size: 13px">    
                <div>dimusnahkan dan dilupuskan.</div>
            </td>
        </tr> 
    </table>
    <br>
    <br>
    <br>
    <br>
    <br>
    <table class="content-table">
        <tr style="padding-top:15px;">
            <td>    
                <div>Tandatangan Pegawai Berkuasa : </div>
                <br>
                <br>
                <div>....................................</div>
                <div>Nama : <?= $model->main->createdByUser->NAMA ?></div>
                <div>Jawatan : <?= $jawatan ?></div>
            </td>
            <td style="width: 200px; ">&nbsp;</td>
            <td>
                <div style=" font-size: 13px;text-align:right;">
                    <div>Tandatangan (Pemilik makanan) : </div>
                    <br>
                <br>
                <div>....................................</div>
                <div>Nama : <?= $model->main->NAMAPENERIMA ?></div>
                <div>No. K/P : <?= $model->main->NOKPPENERIMA ?></div>
                </div>
            </td>
        </tr>  
    </table>
</div>  









