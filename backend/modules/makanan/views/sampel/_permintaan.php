<?php

use common\models\Pengguna;
use common\utilities\DateTimeHelper;
use backend\modules\makanan\models\SampelMakanan;
// use backend\modules\kompaun\utilities\OptionHandler;

/**
 * @var SampelMakanan $model
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

$dataProvider->setPagination(false);
$models = $dataProvider->getModels();
foreach ($models as $model) {
    $rows[] = [
        $model->NOSAMPEL, 
        $model->TRKHSAMPEL,
        $model->JENIS_SAMPEL,
        $model->ID_JENISANALISIS1,
        $model->ID_JENISANALISIS2,
        $model->ID_JENISANALISIS3,
        $model->JENAMA,
        $model->HARGA,
        $model->PEMBEKAL,
        $model->CATATAN,
    ];
}
    
// $sampel1 = SampelMakanan::findAll(['NOSIRI' => $model->NOSIRI]);  

// $sampel1 = Yii::$app->db->createCommand(" SELECT * FROM TBSAMPEL_SM
//             WHERE NOSIRI='$model->NOSIRI'
//             ORDER BY ID
//             FETCH FIRST 3 ROWS ONLY")->queryAll();

// $sampel1 = (new \yii\db\Query())
//     ->select('*')
//     ->from('TBSAMPEL_SM')
//     ->where(['NOSIRI' => $model->NOSIRI])
//     ->limit(3)
//     ->all();

// foreach ($sampel1 as $sampel) {
//     $rows[] = [
//         $sampel->NOSAMPEL, 
//         $sampel->TRKHSAMPEL,
//         $sampel->JENIS_SAMPEL,
//         $sampel->ID_JENISANALISIS2,
//         $sampel->ID_JENISANALISIS3,
//         $sampel->JENAMA,
//         $sampel->HARGA,
//         $sampel->PEMBEKAL,
//         $sampel->CATATAN,
//     ];
// }
    

// var_dump($model->sampels);
// exit();


$pgndaftar = $model->PGNDAFTAR;
$get_data = Yii::$app->db->createCommand(" SELECT DESIGNATION FROM C##MAJLIS.PRUSER
            WHERE USERID=$pgndaftar")->queryOne();

$jawatan = implode('', $get_data);        

$main = $model->main;
// foreach($makmal as $main){
//     $main->makmal;
// }
// var_dump();
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

    /* .kompaun-content-table .title {
        font-weight: bold;
    } */

    .content-table td {
        padding-bottom: 10px;
        vertical-align: top;
        font-size: 12px;
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
        width: 350px;
        height: 30px;
        /* font-size: 20px; */
        padding-left: 5px;
    }
</style>

<div>
    <!-- <img  style="margin-left:40%;margin-right:40%;" height="100" src="<?= Yii::getAlias('@web/images/logo.png') ?>" /> -->
</div>

<h6 class="document-title">JADUAL KETIGA</h6>
<h6 class="document-title">BORANG A</h6>
<h6 class="document-title">[PERATURAN 7 (1)]</h6>
<h6 class="document-title">AKTA MAKANAN 1963</h6>
<h6 class="document-title">PERATURAN-PERATURAN MAKANAN 1985</h6>
<h6 class="document-title">PERMINTAAN BAGI ANALISIS SAMPEL MAKANAN</h6>

<div style="padding-top:15px;text-align:justify;">
    <table class="content-table">
        <tr class="document-title" style="padding-top:15px;">
            <td>    
                <div style=" font-size: 13px;width: 240px;text-align:left;">No. Ruj: (<?= $model->NOSIRI ?>) dlm.MBPJ/060200/T/B9/UKMM/SMPL</div>
            </td>
            <td style="width: 100px; ">&nbsp;</td>
            <td>
                <div style=" font-size: 13px;text-align:right;">
                    <div>Jabatan Kesihatan Persekitaran</div>
                    <div>Tingkat 10, Menara MBPJ</div>
                    <div>Jalan Tengah</div>
                    <div>46200 Petaling Jaya</div>
                    <div>Tel: 03-7956 2939/7955 2381</div>
                    <div>03-7957 5218</div>
                </div>
            </td>
        </tr>  
        <tr class="document-title" style="padding-top:10px;">
            <td style="padding-top:10px;">    
                <div style=" font-size: 13px;width: 240px;text-align:left;">Juruanalisis :</div>
                <div style=" font-size: 13px;width: 240px;text-align:left;"><b><?= !empty($main->makmal->PRGN) ? $main->makmal->PRGN : null ?></b></div>
                <div style="padding-right:60px;">.............................................</div>
                <div style="padding-right:60px;">.............................................</div>
                <!-- <div style="padding-right:60px;">.............................................</div> -->
            </td>
            <td style="width: 70px; ">&nbsp;</td>
            <td style="padding-top:10px;">
                <div style=" font-size: 13px;text-align:right;">
                    <div>Tarikh : <b><?=date('d-m-Y', strtotime($model->TRKHSAMPEL)) ?></b></div>
                    <!-- <div>------------------------------</div> -->
                </div>
            </td>
        </tr> 
        <break> 
    </table>
    <break> 
    <table class="content-table">
        <tr>
            <td style="padding-top:10px;text-align:justify;">    
                <div>Bersama-sama ini saya sertakan sampel makanan / perkakas * dengan diri sendiri/menerusi <?= $model->createdByUser->NAMA ?> / melalui
                mel berdaftar A.T.* untuk tujuan analisis dan laporan tuan. Sampel ini adalah terkandung dalam botol / bungkusan / bekas * dilak dan dilabel seperti berikut :-</div>
            </td>
            <br>
            <!-- <td style="padding-top:20px;width: 50px; ">&nbsp;</td> -->
        </tr>  
    </table>
    <break> 
    <break>
    <table class="sampel-table">
        <tr>
            <td style="font-size: 20px;height: 50px;"><b>No. Rujukan Sampel</b></td>
            <td style="font-size: 20px;height: 50px;"><b>Jenis Sampel/Perkakas</b></td>
            <td style="font-size: 20px;height: 50px;"><b>Tarikh Sampel Diambil</b></td>
        </tr>  
        <tr>
            <?php 
                if($model){
                    foreach($model->sampels as $model1){
                        echo "<tr>";
                        echo "<td style='font-size: 20px;height: 50px;'>". $model1->NOSAMPEL ."</td>";
                        echo "<td style='font-size: 20px;height: 50px;'>". $model1->JENIS_SAMPEL ."</td>";
                        echo "<td style='font-size: 20px;height: 50px;'>". $model1->TRKHSAMPEL ."</td>";
                        echo "</tr>";
                    }	
                }							
            ?>        
        </tr>  
    </table>
    <break>
    <break>
    <table class="content-table">
        <tr>
            <td  style="padding-top:20px;text-align:justify;">Jenis analisis yang dikehendaki bagi sampel itu adalah seperti berikut:- </td>
        </tr>  
    </table>
    <break>
    <break>
    <table class="sampel-table">
        <tr style="font-size: 14px;">
            <td><b>No. Rujukan Sampel</b></td>
            <td><b>Jenis Analisis</b></td>
        </tr>  
        <tr>
            <?php 
                if($model){
                    foreach($model->sampels as $model1){
                        echo "<tr>";
                        echo "<td>". $model1->NOSAMPEL ."</td>";
                        echo "<td>". $model1->jenisAnalisis1->PRGN ."</td>";
                        echo "</tr>";
                    }	
                }							
            ?>        
        </tr>  
    </table>
    <br>
    <br>
    <table class="content-table">
        <tr class="document-title">
            <td style="padding-top:40px;text-align:right;width: 640px">
                <div><?= $model->createdByUser->NAMA ?></div>
                <div><?= $jawatan ?></div>
                <div>--------------------------------------------------------</div>
                <div style=" font-size: 13px;width: 240px;text-align:center;padding-top:50px;">Nama dan Jawatan Pegawai Berkuasa</div>
            </td>  
    </table>
    <br>
    <br>
    <table class="content-table">
        <tr>
            <td style="text-align:center;padding-left:60px;padding-top:25px">
                <div style="font-size:10px">Catatan:- Sampel ini telah diambil menurut prosedur yang ditetapkan di bawah Peraturan-Peraturan Makanan 1985</div>
                <br>
                <div style="font-size:10px">------------------------------------------------------------------------------------------------------------------------------------------------------------------</div>
                <br>
                <div style="font-size:10px">Potong yang mana tidak berkenaan</div>
            </td>  
    </table>
</div>  







