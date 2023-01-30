<?php

use common\models\Pengguna;
use common\utilities\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\modules\makanan\models\SampelMakanan;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\makanan\utilities\OptionHandler;

/**
 * @var Transkolam $model
 */

$dataProvider->setPagination(false);
$models = $dataProvider->getModels();
foreach ($models as $model) {
    $rows[] = [
        $model->IDPARAM, 
        $model->UNIT, 
        $model->NILAIPIAWAI, 
        $model->NILAI1, 
        $model->NILAI2, 
        $model->NILAI3, 
        $model->NILAI4, 
        // $model->TRKHDAFTAR,
        $pgndaftar = $model->PGNDAFTAR,
    ];
}

// $pgndaftar = $model->PGNDAFTAR;
$get_data = Yii::$app->db->createCommand(" SELECT DESIGNATION FROM C##MAJLIS.PRUSER
            WHERE USERID=$pgndaftar")->queryOne();

$jawatan = implode('', $get_data);      

$idmodule = $model->main->IDMODULE;
$listImg = [];
if ($files = $model->main->getAttachments('PKK')) {
    foreach ($files as $key => $file) {
        $listImg[] = Html::img(Yii::getAlias('@web/images/'. $idmodule . '/' . basename($file)), [
            'height' => '230px',
            'width' => '550px',
            'title' => 'Klik untuk besarkan gambar',
            'style' => 'margin:0 15px 20px 0;cursor:pointer',
            'onclick' => 'enlargeImage(this)',
        ]) ;
    }
}
// var_dump($listImg);
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
        /* width: 350px; */
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
        <td style="width:50px"></td>
        <td style="width:290px"></td>
        <td style="text-align:right;">
            <div style=" font-size: 11px;"> &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; No. Ruj &nbsp;: (<?= $model->NOSIRI ?>)MBPJ/060200/T/P48/UKMM/POOL</div>
        </td>
    </tr>
</table>

<div class="centerImage">
    <img style="margin-left:40%;margin-right:40%;" height="80" src="<?= Yii::getAlias('@web/images/logo.png') ?>" />
</div>

<h6 class="document-title">UNIT KAWALAN MUTU MAKANAN, JABATAN PERKHIDMATAN KESIHATAN & PERSEKITARAN</h6>
<h6 class="document-title">MAJLIS BANDARAYA PETALING JAYA</h6>
<h6 class="document-title" style="text-decoration: underline;">BORANG PEMANTAU KUALITI AIR KOLAM RENANG</h6>
<!-- <br> -->
<div style="padding-top:2px;text-align:justify;">
    <table class="content-table">
        <tr>
            <td>    
                <div style=" font-size: 12px;">Nama Premis &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <b><?= $model->lesen->NAMAPREMIS ?></b></div>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>  
        <tr>
            <td>    
                <div style=" font-size: 12px;">Alamat &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;: <b><?= $model->main->PRGNLOKASI ?></b></div>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>  
        <tr>
            <td>    
                <div style=" font-size: 12px;">Jenis Premis &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : <b><?= $model->lesen->JENIS_PREMIS ?></b></div>
            </td>
            <td style="width: 100px;"></td>
            <td style="width: 150px;">    
                <div style=" font-size: 12px;">Tarikh : <b><?=date('d-m-Y', $model->main->TRKHDAFTAR)?>&nbsp; &nbsp;</b></div>
            </td>
            <td>
                <div style=" font-size: 12px;"></b> Masa : <b><?= date('H:i A', $model->main->TRKHDAFTAR) ?></b></div>
            </td>
        </tr>  
        <tr>
            <td>    
                <div style=" font-size: 12px;">Penyelia/Operator&nbsp;: <b><?= $model->main->PKK_NAMAPENYELIA ?>(<?= $model->main->PKK_NOKPPENYELIA ?>)</b></div>
            </td>
            <td style="width: 50px;"></td>
            <td style="width: 10px;">    
                <div style=" font-size: 12px;">Jenis Rawatan Air Kolam :</div>
            </td>
            <td>    
                <div style=" font-size: 12px;"><b><?= OptionHandler::resolve('jenisrawatan', $model->main->PKK_JENISRAWATAN)?></b></div>
            </td>
        </tr>  
        <tr>
            <td>    
                <div style=" font-size: 12px;">Jenis Kolam &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : <b><?= $model->main->PKK_JENISKOLAM?></b></div>
            </td>
        </tr>      
    </table>
    <!-- <br> -->
    <table style="margin-left:13%;margin-right:10%;">
        <tr>
            <td style="text-align:center;">    
                <div><?=implode($listImg)?></div>
            </td>
        </tr>   
    </table>
    <table class="sampel-table" Style="margin-left:10px;margin-bottom:10px">
        <tr>
            <td style="width:30px;"><b>Bil.</b></td>
            <td style="width:150px;"><b>Parameter</b></td>
            <td><b>Nilai Piawai</b></td>
            <td><b>Unit</b></td>
            <td><b>Nilai Bacaan 1</b></td>
            <td><b>Nilai Bacaan 2</b></td>
            <td><b>Nilai Bacaan 3</b></td>
            <td><b>Nilai Bacaan 4</b></td>
        </tr>  
        <tr>
            <?php 
                if($model){
                    $i=0;
                    foreach($model->params as $kolam){
                        echo "<tr>";
                        $i = $i+1;

                        echo "<td>". $i ."</td>";
                        echo "<td>". $kolam->bacaankolam->NAMAPARAM ."</td>";
                        echo "<td style='text-align:center;'>". $kolam->bacaankolam->NILAIPIAWAI ."</td>";
                        echo "<td style='text-align:center;'>". $kolam->bacaankolam->UNIT ."</td>";
                        echo "<td style='text-align:right;'>". $kolam->NILAI1 ."</td>";
                        echo "<td style='text-align:right;'>". $kolam->NILAI2 ."</td>";
                        echo "<td style='text-align:right;'>". $kolam->NILAI3 ."</td>";
                        echo "<td style='text-align:right;'>". $kolam->NILAI4 ."</td>";
                        echo "</tr>";
                    }	
                }							
            ?>        
        </tr>
    </table>
    <table class="content-table" style="margin-bottom:15px">
        <tr>
            <td>
                <div>Anggaran jumlah pengguna kolam ketika persampelan dilakukan : <b><?= $model->main->PKK_JUMPENGGUNA ?></b></div>
                <div>Catatan : <b><?= $model->main->CATATAN ?></b></div>
            </td>
        </tr>
        <!-- <tr>
            <td>
                <div>Catatan : <b><?= $model->main->CATATAN ?></b></div>
            </td>
        </tr> -->
    </table>
    <!-- <br> -->
    <table class="content-table">
        <tr>
            <td style="font-size:13px">    
                <div>Tandatangan Pegawai Berkuasa : </div>
                <!-- <br> -->
                <br>
                <div>.........................................................</div>
                <div>Nama : <?= $model->main->createdByUser->NAMA ?></div>
                <div>Jawatan : <?= $jawatan ?></div>
            </td>
            <td style="width: 250px; ">&nbsp;</td>
            <td style="font-size:13px">
                <div style="text-align:right;">
                    <div>Tandatangan (Pemilik makanan) : </div>
                    <!-- <br> -->
                    <br>
                    <div>.......................................................</div>
                    <div>Nama : <?= $model->main->NAMAPENERIMA ?></div>
                    <div>No. K/P : <?= $model->main->NOKPPENERIMA ?></div>
                </div>
            </td>
        </tr>  
    </table>
</div>  







