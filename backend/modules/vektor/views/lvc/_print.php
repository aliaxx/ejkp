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
use backend\modules\vektor\models\BekasLvc;

$sasarans = $model->sasaranlvc;
$lvc1 = $model->aktivitilvc1;
$lvc2 = $model->aktivitilvc2;
$lvc3 = $model->aktivitilvc3;
$bekaslvc = $model->bekaslvc;
foreach($bekaslvc as $bekas){
    $rows[] = [
        $ai = $bekas->pencapaian['ai'],
        $bi = $bekas->pencapaian['bi'],
        $ci = $bekas->pencapaian['ci'],
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
            <div style=" font-size: 11px;"> No. Ruj &nbsp;: (<?= $model->NOSIRI ?>)eDengue/KV.PPA/11.1</div>
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
        <td style="width:40px"></td>
        <td style="text-align:center;font-size:16px;">
            <div>
                <h6 class="document-title">LAPORAN AKTIVITI LARVACIDING</h6>
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
    <div>
    <table class="sampel-table">
            <tr>
                <td colspan="3" style="border-style:none;padding-left: 0px;"><h6 class="document-title" style="text-align:left;">A. MAKLUMAT KES</h6></td>      
                <td colspan="2" style="border-style:none;padding-left: 0px;"><h6 class="document-title" style="text-align:left">B. MAKLUMAT AKTIVITI</h6></td>
            </tr>
            <!-- <tr style="border-style:none;"><td></td></tr> -->
            <tr>
                <td style="width:130px;"><?= Yii::t('app', 'NAMA KES RUJUK') ?></td>
                <td style="width:200px"><b><?=$model->V_RUJUKANKES?></b></td> 
                <td style="border-style:none;width:5px"></td>      
                <td style="width:130px"><?= Yii::t('app', 'TARIKH AKTIVITI') ?></td>
                <td style="width:200px"><b><?=date('d-m-Y', strtotime($model->TRKHMULA))?></b></td>
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
                <td><?= Yii::t('app', 'TARIKH NOTIFIKASI KES') ?></td>
                <td><b><?=date('d-m-Y H:i A', strtotime($model->V_TRKHNOTIFIKASI))?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'NEGERI') ?></td>
                <td><b>Selangor</b></td>
                <td style="border-style:none;width:5px"></td>      
                <td><?= Yii::t('app', 'MINGGU EPID') ?></td>
                <td><b><?=$model->V_MINGGUEPID?></b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'DAERAH') ?></td>
                <td><b>Petaling</b></td>
                <td style="border-style:none;width:5px"></td>      
                <td><?= Yii::t('app', 'KAWASAN OPERASI') ?></td>
                <td><b>PBT</b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'MUKIM') ?></td>
                <td><b><?=isset($model->mukim->PRGN) ? $model->mukim->PRGN :null?></b></td>
                <td style="border-style:none;width:5px"></td>      
                <td><?= Yii::t('app', 'NAMA PBT') ?></td>
                <td><b>MBPJ</b></td>
            </tr>
            <tr>
                <td><?= Yii::t('app', 'LOKALITI') ?></td>
                <td><b><?=isset($model->lokaliti->PRGN) ? $model->lokaliti->PRGN :null?></b></td>
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
        </table> 
    </div>
    <br>
    <h6 class="document-title" style="text-align:left">C. AKTIVITI LARVACIDING</h6>
    <table>
        <tr>
            <td>
                <div style="font-size:13px"><b>A. Premis Disembur Larvisid</b></div>
                <br>
                <table class="sampel-table">
                    <tr>
                        <td style="width:130px"><?= Yii::t('app', 'Sasaran Premis') ?></td>
                        <td style="text-align:right;width:200px"><b><?=!empty($lvc1->V_SASARANPREMIS) ? $lvc1->V_SASARANPREMIS:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Premis') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc1->V_BILPREMIS) ? $lvc1->V_BILPREMIS:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Bekas') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc1->V_BILBEKAS) ? $lvc1->V_BILBEKAS:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jenis Racun') ?></td>
                        <td style="text-align:right;"><b><?=isset($lvc1->racun->PRGN) ? $lvc1->racun->PRGN: null?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jum. Racun (gm)') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc1->V_JUMRACUN) ? $lvc1->V_JUMRACUN:null?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Mesin') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc1->V_BILMESIN) ? $lvc1->V_BILMESIN:null ?></b></td>
                    </tr>
                </table>
            </td>
            <td style="width:10px"></td>
            <td>
                <div style="font-size:13px"><b>B. Premis Disembur Mistblower</b></div>
                <br>
                <table class="sampel-table">
                    <tr>
                        <td style="width:130px"><?= Yii::t('app', 'Sasaran Premis') ?></td>
                        <td style="text-align:right;width:200px"><b><?=!empty($lvc2->V_SASARANPREMIS) ? $lvc1->V_SASARANPREMIS:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Premis') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc2->V_BILPREMIS) ? $lvc2->V_BILPREMIS:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Bekas') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc2->V_BILBEKAS) ? $lvc2->V_BILBEKAS:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jenis Racun') ?></td>
                        <td style="text-align:right;"><b><?=isset($lvc2->racun->PRGN) ? $lvc2->racun->PRGN: null?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jum. Racun (gm)') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc2->V_JUMRACUN) ? $lvc2->V_JUMRACUN:null?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Mesin') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc2->V_BILMESIN) ? $lvc2->V_BILMESIN:null ?></b></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top:15px">
                <div style="font-size:13px;"><b>C. Abating</b></div>
                <br>
                <table class="sampel-table">
                    <tr>
                        <td style="width:130px"><?= Yii::t('app', 'Sasaran Premis') ?></td>
                        <td style="text-align:right;width:200px"><b><?=!empty($lvc3->V_SASARANPREMIS) ? $lvc3->V_SASARANPREMIS:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Premis') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc3->V_BILPREMIS) ? $lvc3->V_BILPREMIS:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Bekas') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc3->V_BILBEKAS) ? $lvc3->V_BILBEKAS:null ?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jenis Racun') ?></td>
                        <td style="text-align:right;"><b><?=isset($lvc3->racun->PRGN) ? $lvc3->racun->PRGN: null?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Jum. Racun (gm)') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc3->V_JUMRACUN) ? $lvc3->V_JUMRACUN:null?></b></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Bil. Mesin') ?></td>
                        <td style="text-align:right;"><b><?=!empty($lvc3->V_BILMESIN) ? $lvc3->V_BILMESIN:null ?></b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <!-- <p style="page-break-after: always"></p> -->
    <h6 class="document-title" style="text-align:left">D. TUJUAN AKTIVITI</h6>
    <!-- <br> -->
    <div style="margin-left:30px;font-size:13px">Jenis Pemeriksaan &nbsp; : <b><?= OptionHandler::resolve('jenis-sembur', $model->V_JENISSEMBUR) ?></b></div>
    <br>
    <?php
        if($model->V_JENISSEMBUR == 1){
            echo "<div style='margin-left:30px;font-size:13px'>". "Kategori Lokaliti ". "&nbsp; &nbsp; &nbsp;: " . "<b>" . OptionHandler::resolve('kat-lokaliti', $model->V_KATLOKALITI) . "</b>" ."</div>";
            echo "<br>";
            echo "<div style='margin-left:30px;font-size:13px'>". "Pusingan &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: "."<b>" .$model->V_PUSINGAN. "</b>" ."</div>";
        }else{
            echo "<div style='margin-left:30px;font-size:13px'>". "Surveilan &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ". "<b>" .OptionHandler::resolve('ptp-surveilan', $model->V_ID_SUREVEILAN). "</b>" ."</div>";
        }
    ?>
    <br>
    <h6 class="document-title" style="text-align:left"><b>Tempoh Masa Pemeriksaan Dilakukan Selepas Notifikasi Kes</b></h6>
    <table style="margin-left:30px">
        <tr>
            <td style="font-size:13px">
                <div>Tempoh Masa : <b><?=OptionHandler::resolve('tempoh', $model->V_TEMPOH)?></b></div>
            <td>
            <td style="width:50px"></td>
            <?php
                if($model->V_TEMPOH == 2){
                    echo "<td style='font-size:13px'>". "Alasan : " . "<b>" . $model->alasan->PRGN . "</b>" ."</td>";
                }
            ?>
        </tr>
    </table>
    <!-- <p style="page-break-after: always"></p> -->
    <h6 class="document-title" style="text-align:left">E. PENCAPAIAN LIPUTAN PREMIS PPA PADA KESELURUHAN</h6>
    <h6 class="document-title" style="text-align:left;padding-left:15px">SASARAN JUMLAH JENIS PREMIS DALAM LINKUNGAN 50 M</h6>
    <table class="sampel-table">
        <thead>
            <tr>
                <td style="width:280px" rowspan="2">Jenis Premis </td>
                <td style="width:250px;text-align:center" colspan="4">Jumlah Premis</td>
            </tr>
            <tr>
                <td>Sasaran (50 M)</td>
                <td>Pencapaian</td>
                <td>Peratusan (%)</td>
                <td>Jumlah Positif</td>
            </tr>        
        </thead>
        <tr>
            <?php 
                if($model){
                    foreach($sasarans as $sasaran){
                        echo "<tr>";
                            echo "<td style='font-weight: bold;'>". $sasaran->premis1->PRGN ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". $sasaran->SASARAN ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". $sasaran->PENCAPAIAN ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". " " ."</td>";
                            echo "<td style='font-weight: bold;text-align:right'>". $sasaran->JUMPOSITIF ."</td>";
                        echo "</tr>";
                    }	
                }							
            ?>        
        </tr>
        <tr class="sampel-table">
            <td><b><?= Yii::t('app', 'Jumlah Premis') ?></b></td>
            <?php 
                if($sasarans){
                    echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran['sasaran'] ."</td>";
                    echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran['pencapaian'] ."</td>";
                    echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran['peratusan'] ."</td>";
                    echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran['positif'] ."</td>";
                }
            ?>
        </tr>
        <tr class="sampel-table">
            <td style="text-align:right;" colspan="4"><b><?= Yii::t('app', 'Bil. Penduduk') ?></b></td>
            <td style="text-align:right;" ><b><?=$model->V_BILPENDUDUK?></b></td>
        </tr>
    </table>
    <br>
    <p style="page-break-after: auto"></p>
    <h6 class="document-title" style="text-align:left">F. LIPUTAN PREMIS PPA DALAM DAN LUAR RUMAH</h6>
    <table class="sampel-table" style="width:100%">
            <tr>
                <tr>
                    <td style="text-align:left"><b><?= Yii::t('app', 'JENIS LIPUTAN') ?></b></td>
                    <td style="text-align:left"><b><?= Yii::t('app', 'KAWASAN LIPUTAN') ?></b></td>
                    <td style="text-align:left"><b><?= Yii::t('app', 'JUMLAH PREMIS') ?></b></td>
                </tr>
                <tr>
                    <td rowspan="2"><?= Yii::t('app', 'PEMERIKSAAN TIDAK LENGKAP') ?></td>
                    <td><?= Yii::t('app', 'Dalam Rumah Sahaja') ?></td>
                    <td style="text-align:right"><b><?=$model->V_TLENGKAPDALAM?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Luar Rumah Sahaja') ?></td>
                    <td style="text-align:right"><b><?=$model->V_TLENGKAPLUAR?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'PEMERIKSAAN LENGKAP') ?></td>
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
                <?php 
                    if($sasarans){
                        echo "<td style='font-weight: bold;text-align:right'>". $sasaran->jumSasaran['pencapaian'] ."</td>";
                    }
                ?>        
            </tr>
    </table>
    <br>
    <br>
    <h6 class="document-title" style="text-align:left">G. SEBAB PREMIS TIDAK DIPERIKSA</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
            <tr>
                <tr>
                    <td style="width:70%; text-align:left"><b><?= Yii::t('app', 'ALASAN') ?></b></td>
                    <td style="width:30%; text-align:left"><b><?= Yii::t('app', 'JUMLAH PREMIS') ?></b></td>
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
    <h6 class="document-title" style="text-align:left">H. JENIS-JENIS BEKAS DIPERIKSA</h6>
    <br>
    <table>
        <tr>
            <td>
                <?php if(!empty($model->bekaslvc1)): ?>
                <h6 class="document-title" style="text-align:left;font-size:12px">PEMBIAKAN DALAM RUMAH</h6>
                <br>
                    <table class="sampel-table">
                        <tr>
                        <td><?= Yii::t('app', 'Bil') ?></td>
                            <td style="width:150px"><?= Yii::t('app', 'Jenis Bekas') ?></td>
                            <td style="width:20px"><?= Yii::t('app', 'Bil. Bekas') ?></td>
                            <td style="width:50px"><?= Yii::t('app', 'Bil. Bekas Potensi Pembiakan (Bertakung Air)') ?></td>
                            <td style="width:50px"><?= Yii::t('app', 'Bil. Bekas Positif Pembiakan') ?></td>
                            <td style="width:50px"><?= Yii::t('app', 'Keputusan Pembiakan (Spesis Nyamuk)') ?></td>
                            <td style="width:110px"><?= Yii::t('app', 'Positif Purpa') ?></td>
                            <td style="width:100px"><?= Yii::t('app', 'Catatan') ?></td>				
                        </tr>
                        <tr>
                            <?php 
                            if($model){
                                $i=0;
                                foreach($model->bekaslvc1 as $jenisbekas){
                                    echo "<tr>";
                                        $i = $i+1;

                                        echo "<td>". $i ."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->JENISBEKAS ."</td>";
                                        echo "<td style='font-weight: bold;text-align:right;'>". $jenisbekas->BILBEKAS ."</td>";
                                        echo "<td style='font-weight: bold;text-align:right;'>". $jenisbekas->BILPOTENSI ."</td>";
                                        echo "<td style='font-weight: bold;text-align:right'>". $jenisbekas->BILPOSITIF ."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->KEPUTUSAN."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->PURPA ."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->CATATAN ."</td>";
                                    echo "</tr>";
                                }	
                            }							
                            ?>        
                        </tr>
                        <tr class="sampel-table">
                            <!-- <td style="height:30px"><b></b></td> -->
                            <td style="height:30px" colspan="2"><b>JUMLAH</b></td>
                            <td style="text-align:right"><b><?=$bekas->pencapaian['bilbekas1']?></b></td>
                            <td style="text-align:right"><b><?=$bekas->pencapaian['bilpotensi1']?></b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                <?php endif; ?>
            </td>
        </tr>
        <br>
        <br>
        <tr>
            <td>
                <?php if(!empty($model->bekaslvc2)): ?>
                    <h6 class="document-title" style="text-align:left;font-size:12px">PEMBIAKAN LUAR RUMAH</h6>
                    <br>
                    <table class="sampel-table">
                        <tr>
                            <td><?= Yii::t('app', 'Bil') ?></td>
                            <td style="width:150px"><?= Yii::t('app', 'Jenis Bekas') ?></td>
                            <td style="width:20px"><?= Yii::t('app', 'Bil. Bekas') ?></td>
                            <td style="width:50px"><?= Yii::t('app', 'Bil. Bekas Potensi Pembiakan (Bertakung Air)') ?></td>
                            <td style="width:50px"><?= Yii::t('app', 'Bil. Bekas Positif Pembiakan') ?></td>
                            <td style="width:50px"><?= Yii::t('app', 'Keputusan Pembiakan (Spesis Nyamuk)') ?></td>
                            <td style="width:110px"><?= Yii::t('app', 'Positif Purpa') ?></td>
                            <td style="width:100px"><?= Yii::t('app', 'Catatan') ?></td>				
                        </tr>
                        <tr>
                            <?php 
                            if($model){
                                $i=0;
                                foreach($model->bekaslvc2 as $jenisbekas){
                                    echo "<tr>";
                                        $i = $i+1;

                                        echo "<td>". $i ."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->JENISBEKAS ."</td>";
                                        echo "<td style='font-weight: bold;text-align:right;'>". $jenisbekas->BILBEKAS ."</td>";
                                        echo "<td style='font-weight: bold;text-align:right;'>". $jenisbekas->BILPOTENSI ."</td>";
                                        echo "<td style='font-weight: bold;text-align:right'>". $jenisbekas->BILPOSITIF ."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->KEPUTUSAN."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->PURPA ."</td>";
                                        echo "<td style='font-weight: bold;'>". $jenisbekas->CATATAN ."</td>";
                                    echo "</tr>";
                                }	
                            }							
                            ?>        
                        </tr>
                        <tr class="sampel-table">
                            <!-- <td style="height:30px"><b></b></td> -->
                            <td style="height:30px" colspan="2"><b>JUMLAH</b></td>
                            <td style="text-align:right"><b><?=$bekas->pencapaian['bilbekas2']?></b></td>
                            <td style="text-align:right"><b><?=$bekas->pencapaian['bilpotensi2']?></b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                <?php endif; ?>
            </td>
        </tr>
        <br>
        <tr>
            <td><b>Bilangan Bekas Dimusnahkan : <?=$model->PTP_BILBEKASMUSNAH?></b></td>
        </tr>
    </table>
    <br>
    <p style="page-break-after: auto"></p>
    <h6 class="document-title" style="text-align:left">I. PENGIRAAN PENCAPAIAN</h6>
    <table class="sampel-table" style="width:100%">
        <thead style="color:#000000">
            <tr>
                <tr>
                    <td><?= Yii::t('app', '1% Liputan Premis Diperiksa Lengkap (Dalam & Luar Rumah)') ?></td>
                    <td style="text-align:right"><b></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Indeks Aedes (AI)') ?></td>
                    <td style="text-align:right"><b><?=bcdiv($ai, 1, 2)?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Indeks Breteau (BI)') ?></td>
                    <td style="text-align:right"><b><?=bcdiv($bi, 1, 2)?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Indeks Bekas (CI)') ?></td>
                    <td style="text-align:right"><b><?=bcdiv($ci, 1, 2)?></b></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', 'Indeks Purpa (HPI)') ?></td>
                    <td style="text-align:right"><b></b></td>
                </tr>
            </tr>
        </thead>
    </table>
    <br>
    <p style="page-break-after: always"></p>
    <h6 class="document-title" style="text-align:left">J. NAMA AHLI PASUKAN</h6>
    <table class="sampel-table" style="width:100%">
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
    </table>
    <br>
    <h6 class="document-title" style="text-align:left">J. MAKLUMAT PENCATAT</h6>
    <table class="sampel-table" style="width:100%">
        <tr>
            <td style="width:60%"><?= Yii::t('app', 'Nama Pegawai') ?></td>
            <td style="width:40%"><?= Yii::t('app', 'Jawatan') ?></td>
        </tr>
        <tr>
            <td style="width:60%"><b><?= $ketua->pengguna0->NAMA ?></b></td>
            <td style="width:40%"><b><?= $ketua->jawatan?></b></td>
        </tr>
    </table>
    <br>
    <h6 class="document-title" style="text-align:left;font-size:13px;">LAIN-LAIN CATATAN</h6>
    <div style="font-size:13px;">Catatan : <?=$model->CATATAN?></div>
</div>








