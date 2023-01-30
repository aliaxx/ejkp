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

// $dataProvider->setPagination(false);
// $models = $dataProvider->getModels();
// foreach ($models as $model) {
//     $rows[] = [
//         $model->NOSIRI,
//         $model->NOSAMPEL,
//     ];
// }

// var_dump($model->NOSIRI);
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
        /* margin: auto; */
        border-collapse: separate;
        border: 1px solid black;
        /* width: 150px; */
        height: 25px;
        font-size: 13px;
        padding-left: 10px;
        margin-right: 10px;

    }

    .jenis-table td{
        /* margin: auto; */
        border-collapse: separate;
        border: 1px solid black;
        /* width: 150px; */
        height: 25px;
        font-size: 15px;
        padding-left: 5px;
        margin-right: 5px;

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
<br>
<div style="padding-top:2px;text-align:justify;">
    <br>
    <div>
        <table class="sampel-table" style="width:100%;">
            <tr>
                <td colspan="2" style="text-align:center;height:50px">
                    <div>
                        <h6 class="document-title">BORANG PREMIS POSITIF PEMBIAKAN (BORANG PPA - 2)</h6>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2"><div style="font-size:13px"><b>A. Alamat Premis</b></div></td>
            </tr>
            <tr>
                <td style="width:30%"><?= Yii::t('app', 'No./Lot') ?></td>
                <td style="width:70%"><b><?=$model->NOLOT?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Tingkat/ Blok/ Jalan') ?></td>
                <td><b><?=$model->BANGUNAN?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Taman/  kampung/Blok') ?></td>
                <td><b><?=$model->TAMAN?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Nama Orang Yang Ditemui') ?></td>
                <td><b><?=$model->NAMAPESALAH?></b></td>
            </tr>
            <tr>
                <td colspan="2"><div style="font-size:13px"><b>B. Jenis Premis</b></div></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Jenis Premis') ?></td>
                <td><b><?=!empty($model->premis->PRGN) ? $model->premis->PRGN : null?></b></td>
            </tr>
            <tr>
                <td colspan="2"><div style="font-size:13px"><b>C. Tarikh & Masa Pemeriksaan</b></div></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Tarikh & Masa Pemeriksaan') ?></td>
                <td><b><?=date('d/m/Y H:i A', strtotime($model->TRKHSALAH))?></b></td>
            </tr>
            <tr>
                <td colspan="2"><div style="font-size:13px"><b>D. Jenis Pembiakan & Tindakan Penguatkuasaan</b></div></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Liputan Pemeriksaan') ?></td>
                <td><b><?=!empty($model->liputan->PRGN) ? $model->liputan->PRGN : null ?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Jenis Pembiakan') ?></td>
                <td><b><?=!empty($model->jenisPembiakan->PRGN) ? $model->jenisPembiakan->PRGN : null ?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'No Daftar Sampel') ?></td>
                <td><b><?=$model->NOSAMPEL?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Tindakan Untuk Premis Positif') ?></td>
                <td><b><?=!empty($model->tindakan->PRGN) ? $model->tindakan->PRGN : null ?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Sebab Kompaun Tidak Diberi') ?></td>
                <td><b><?=!empty($model->sebab->PRGN) ? $model->sebab->PRGN:null?></b></td>
            </tr>           
            <tr>
                <td colspan="2"><div style="font-size:13px"><b>E. Jenis-jenis Bekas Pembiakan Positif Pembiakan</b></div></td>
            </tr>
        </table>
    </div>
    <div>
        <table class="jenis-table">
            <tr>
                <td style="width:3%"><?= Yii::t('app', 'Bil') ?></td>
                <td style="width:10%"><?= Yii::t('app', 'Jenis Bekas') ?></td>
                <td style="width:10%"><?= Yii::t('app', 'Bil. Bekas') ?></td>
                <td style="width:10%"><?= Yii::t('app', 'Bil. Bekas Potensi Pembiakan (Bertakung Air)') ?></td>
                <td style="width:10%"><?= Yii::t('app', 'Bil. Bekas Positif Pembiakan') ?></td>
                <td style="width:10%"><?= Yii::t('app', 'Keputusan Pembiakan (Spesis Nyamuk)') ?></td>
                <td style="width:10%"><?= Yii::t('app', 'Positif Purpa') ?></td>
                <td style="width:10%"><?= Yii::t('app', 'Dalam/Luar Rumah') ?></td>				
            </tr>
            <tr>
                <?php 
                if($model){
                    $i=0;
                    foreach($model->bekas as $jenisbekas){
                        echo "<tr>";
                            $i = $i+1;

                            echo "<td>". $i ."</td>";
                            echo "<td style='font-weight: bold;'>". $jenisbekas->JENISBEKAS ."</td>";
                            echo "<td style='font-weight: bold;'>". $jenisbekas->BILBEKAS ."</td>";
                            echo "<td style='font-weight: bold;'>". $jenisbekas->BILPOTENSI ."</td>";
                            echo "<td style='font-weight: bold;'>". $jenisbekas->BILPOSITIF ."</td>";
                            echo "<td style='font-weight: bold;'>". $jenisbekas->jenisPembiakan->PRGN."</td>";
                            echo "<td style='font-weight: bold;'>". $jenisbekas->PURPA ."</td>";
                            echo "<td style='font-weight: bold;'>". $jenisbekas->liputan->PRGN ."</td>";
                        echo "</tr>";
                    }	
                }							
                ?>        
            </tr>
        </table>
    </div>
    <div>
        <table class="sampel-table" style="width:100%">
            <tr>
                <td colspan="2"><div style="font-size:13px"><b>F. Bilangan Bekas Dimusnahkan</b></div></td>
            </tr>
            <tr>
                <td style="width:30%"><?= Yii::t('app', 'Bilangan Bekas Dimusnahkan') ?></td>
                <td style="width:70%"><b><?=$model->BILBEKASMUSNAH?></b></td>
            </tr>
            <tr>
                <td colspan="2"><div style="font-size:13px"><b>G. Koordinasi Pembiakan Positif</b></div></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Latitud') ?></td>
                <td><b><?=$model->LATITUDE?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'Longitud') ?></td>
                <td><b><?=$model->LONGITUDE?></b></td>
            </tr>
        </table>    
    </div>
</div>








