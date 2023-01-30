<?php

use common\models\Pengguna;
use common\utilities\DateTimeHelper;
use backend\modules\makanan\models\SampelMakanan;
use backend\modules\lawatanmain\models\LawatanMain;
/**
 * @var SampelMakanan $sampel
 */

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
$get_data = Yii::$app->db->createCommand(" SELECT DESIGNATION FROM C##MAJLIS.PRUSER
            WHERE USERID=$pgndaftar")->queryOne();
$jawatan = implode('', $get_data);  

$main = $sampel->main;

// $model = LawatanMain::findAll(['NOSIRI' => $sampel->NOSIRI]);  
// foreach ($model as $model->main) {
//     $rows[] = [
//         $main->NAMAPENERIMA, 
//         $main->NOKPPENERIMA,
//     ];
// }

// var_dump($main->NAMAPENERIMA);
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
        /* padding-bottom: 10px; */
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
        /* width: 350px; */
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
<h6>________________________________________________________________________________________________________________________<h6>
<h6 class="document-title" style="margin-top:0px">AKTA MAKANAN 1983</h6>
<h6 class="document-title">(Seksyen 6(1))</h6>
<h6 class="document-title">Notis Persampelan Makanan</h6>

<div style="padding-top:25px;">
    <table class="content-table">
        <tr style="padding-top:15px;">
            <td>
                <div>Kepada</div>
                <br>
            </td>
        </tr> 
        <tr>
            <td style="text-align:center;padding-bottom:1px">
                <div><b><?= $main->NAMAPENERIMA ?> (<?= $main->NOKPPENERIMA ?>)</b></div>
                <div>_______________________________________________________________________________________________________________</div>
                <div><b> (Nama dan No. Kad Pengenalan) </b></div>
                <br>
                <br>
            </td>
        </tr>  
        <tr>
            <td>
                <div>Sila ambil perhatian bahawa, saya</div>
                <br>
            </td>
        </tr>  
        <tr>
            <td style="text-align:center">
                <div><b><?= $sampel->createdByUser->NAMA ?></b></div>
                <div>_______________________________________________________________________________________________________________</div>
                <div><b> (Nama Pegawai Berkuasa) </b></div>
            </td>
        </tr>  
    </table>
    <br> 
    <table class="content-table">
        <tr>
            <td style="padding-top:10px;text-align:justify;">    
                <div>Telah membeli/memperoleh sampel makanan yang tercatat dibawah untuk analisa oleh seorang Juruanalisis</div>
            </td>
            <br>
        </tr>  
    </table>
    <table class="sampel-table">
        <tr style="width:250px;height:70px;">
            <td style="width:50px;"><b>Bil.</b></td>
            <td><b>Tarikh & Masa Sampel Diambil</b></td>
            <td><b>Jenis Makanan</b></td>
            <td><b>Jenama Makanan</b></td>
            <td><b>Pengimport/Pengilang/ Pembungkus/Pembekal</b></td>
        </tr>  
        <tr>
            <?php 
                if($sampel){
                    $i=0;
                    foreach($sampel1 as $model1){
                        echo "<tr>";
                            $i = $i+1;
                            echo "<td>". $i ."</td>";
                            echo "<td>". $model1->TRKHSAMPEL ."</td>";
                            echo "<td>". $model1->JENIS_SAMPEL ."</td>";
                            echo "<td>". $model1->JENAMA ."</td>";
                            echo "<td>". $model1->PEMBEKAL ."</td>";
                        echo "</tr>";
                    }	
                }	
            ?>        
        </tr>  
    </table>
    <br>
    <br>
    <table class="content-table">
        <tr style="padding-top:15px;">
            <td>
                <br>
                <br>
                <div>Bertarikh pada <b><?=date('d-m-Y', strtotime($sampel->TRKHSAMPEL)) ?></b></div>
                <br>
                <br>
            </td>
        </tr> 
        <tr>
            <td style="text-align:left">
                <br>
                <div>____________________________________</div>
            </td>
        </tr> 
        <tr>
            <td style="padding-left:39px">
                <div><b> (Pegawai Berkuasa) </b></div>
                <br>
                <br>
            </td>
        </tr>   
        <tr>
            <td>
                <br>
                <div>Tuan/puan dikehendaki untuk menyerahkan salinan notis ini kepada pengimport / pengilang / pembungkus / pembekal makanan berkenaan.</div>
                <br>
            </td>
        </tr>  
        <tr>
        <td style="text-align:left">
                <br>
                <br>
                <div>Saya mengakui menerima notis ini</div>
                <br>
                <br>
                <div>____________________________________</div>
                <br>
            </td>
        </tr>  
        <tr>
            <td style="text-align:left;padding-left:300px;" >
                <div>Nama   : <?= $main->NAMAPENERIMA ?></div>
                <div>No. K/P   : <?= $main->NOKPPENERIMA ?></div>
                <div>Cop Rasmi   :</div>
            </td>
        </tr>  
    </table>
</div>  







