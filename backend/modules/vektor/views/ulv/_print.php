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


$sasarans = $model->sasaranulv;
// $sasaran1 = Yii::$app->db->createCommand("SELECT SUM(SASARAN1) FROM TBSASARAN_ULV WHERE NOSIRI = '$model->NOSIRI'")->queryScalar();
// $pencapaian1 = Yii::$app->db->createCommand("SELECT SUM(PENCAPAIAN1) FROM TBSASARAN_ULV WHERE NOSIRI = '$model->NOSIRI'")->queryScalar();
// $sasaran2 = Yii::$app->db->createCommand("SELECT SUM(SASARAN2) FROM TBSASARAN_ULV WHERE NOSIRI = '$model->NOSIRI'")->queryScalar();
// $pencapaian2 = Yii::$app->db->createCommand("SELECT SUM(PENCAPAIAN2) FROM TBSASARAN_ULV WHERE NOSIRI = '$model->NOSIRI'")->queryScalar();
// $jumpremis = implode('', $jum); 
// $jumpencapaian = $gredpremis->markahpremis['sum'];
// $jumsasaran = $gredpremis->markahpremis['gred'];

$ahlis = Yii::$app->db->createCommand("SELECT COUNT(IDPENGGUNA) FROM TBLAWATAN_PASUKAN WHERE NOSIRI ='$model->NOSIRI' AND JENISPENGGUNA='2'")->queryScalar();
$ketua = $model->ketuapasukan0;

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
            <td><b><?=$ketua->pengguna0->NAMA?></b></td>
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
    <br>
    <h6 class="document-title" style="text-align:left">H. PENYEDIA LAPORAN</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
                <tr>
                    <td style="width:60%"><?= Yii::t('app', 'LAPORAN DISEDIAKAN OLEH') ?></td>
                    <td style="width:40%"><?= Yii::t('app', 'JAWATAN') ?></td>
                </tr>
                <tr>
                    <td style="width:60%"><b><?= $ketua->pengguna0->NAMA ?></b></td>
                    <td style="width:40%"><b><?= $ketua->jawatan?></b></td>
                </tr>
        </thead>
    </table>
</div>








