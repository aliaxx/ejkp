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

$ahlis = Yii::$app->db->createCommand("SELECT COUNT(IDPENGGUNA) FROM TBLAWATAN_PASUKAN WHERE NOSIRI ='$model->NOSIRI' AND JENISPENGGUNA='2'")->queryScalar();
$ketua = $model->ketuapasukan0;

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
            <td style="font-size:14px"><b><?=$ketua->pengguna0->NAMA?></b></td>
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
                    <?php if($dalamluar1): ?>
                        <td style="width:30%"><?= Yii::t('app', 'Jenis Racun Serangga') ?></td>
                        <td style="width:10%"><?= Yii::t('app', 'Amaun Racun (ml)') ?></td>
                        <td style="width:20%"><?= Yii::t('app', 'Jenis Pelarut') ?></td>
                        <td style="width:10%"><?= Yii::t('app', 'Amaun Pelarut (ml)') ?></td>
                        <td style="width:10%"><?= Yii::t('app', 'Amaun Petrol (liter)') ?></td>
                        <td style="width:10%"><?= Yii::t('app', 'Bil. Caj') ?></td>
                        <td style="width:10%"><?= Yii::t('app', 'Bil. Mesin Digunakan') ?></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <?php 
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
                <?php if($model->ahli): ?>
                    <td style="width:60%"><?= Yii::t('app', 'Nama Anggota') ?></td>
                    <td style="width:40%"><?= Yii::t('app', 'Jawatan') ?></td>
                <?php endif; ?>
            </tr>
            <tr>
                <?php 
                    foreach($model->ahli as $pasukan){
                        echo "<tr>";
                            echo "<td style='font-weight: bold;'>". $pasukan->pengguna0->NAMA ."</td>";
                            echo "<td style='font-weight: bold;'>". $pasukan->jawatan ."</td>";
                        echo "</tr>";
                    }	
                ?>        
            </tr>
        </thead>
    </table>

</div>








