<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\utilities\OptionHandler;

use common\utilities\DateTimeHelper;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPemilik;

$dataProvider->setPagination(false);
$models = $dataProvider->getModels();
foreach ($models as $model) {
    $rows[] = [
        $model->NOSIRI, 
    ];
}
// var_dump($model->NOSIRI );
// exit;

if($model->transpremisrec){
    // var_dump($model->NOSIRI );
    $jumlahmarkah = $model->markahpremis['sum'];
    $gred = $model->markahpremis['gred'];
    $sum_totalmark = $model->markahpremis['sum_totalmark'];
    $sum_demerit = $model->markahpremis['sum_demerit'];
}else {
    
    $jumlahmarkah = '';
    $gred = '';
}

$main = LawatanMain::findAll(['NOSIRI' => $model->NOSIRI]);  
foreach ($main as $mains) {
    $rows[] = [
        $mains->PPM_BILPENGENDALI, 
        $mains->PPM_SUNTIKAN_ANTITIFOID,
        $mains->PPM_KURSUS_PENGENDALI,
        $mains->TRKHMULA,
        $mains->PGNDAFTAR,
    ];
}

$pemilik = LawatanPemilik::findAll(['NOSIRI' => $model->NOSIRI]);  
// var_dump($pemilik);
// exit;
foreach ($pemilik as $pemiliks) {
    $rows[] = [
        $pemiliks->NAMAPEMOHON,
        $pemiliks->NOKPPEMOHON,
        $pemiliks->NAMASYARIKAT, 
        $pemiliks->NOTEL,
        $pemiliks->ALAMAT1,
        $pemiliks->ALAMAT2,
        $pemiliks->ALAMAT3,
        $pemiliks->POSKOD,
        
    ];
}


?>

<style>
    table {
    margin: auto;
    border-collapse: collapse;
    width: 100%;
}

table, td, tr {
  border: 1px solid black;
}

/* add border */
table td, table th {
    font-family: Tahoma, Arial;
    padding: 5px;
}

tr td:nth-child(2) { 
   text-align: left;
}

.column {
  float: left;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

.left {
  width: 50%;
}

.right {
  width: 50%;
}


table th{
    /* background-color: orange; */
}
</style>

<div>
    <img  style="margin-left:40%;margin-right:40%;" height="100" src="<?= Yii::getAlias('@web/images/logo.png') ?>" />
</div>
<div>
<div style="font-size:10px;" align="right"><b>No Siri Borang: <?= $mains->NOSIRI ?></div>
<div class="print-pdf">
<div style="font-size:18px;" align="center"><b>BORANG PEMERIKSAAN DAN PENGGREDAN </b>
<br><b>PREMIS MAKANAN</b></div>

<break>

<div style="padding-top:20px;text-align:justify;">
    <table style="width:100%" >
       
        <tr>
            <th style="width:20%">Nama Pelesen</th>
                <td style="width:30%"><?= $pemiliks->NAMAPEMOHON?></td>
            <th style="width:20%">No. K/P Pelesen</th>
                <td style="width:30%"><?= $pemiliks->NOKPPEMOHON?></td>
        </tr>
        <tr>
            <th style="width:20%">Nama Syarikat</th>
                <td style="width:30%"><?= $pemiliks->NAMASYARIKAT?></td>
            <th style="width:20%">No. Tel</th>
                <td style="width:30%"><?= $pemiliks->NOTEL?></td>
        </tr>
        <tr>
            <th style="width:20%">Alamat Premis</th>
                <td style="width:30%"><?= $pemiliks->ALAMAT1.','.$pemiliks->ALAMAT2.','.$pemiliks->ALAMAT3?>
                <br><?= $pemiliks->POSKOD?></td>
            <th style="width:20%">No. Ruj. Lesen</th>
                <td style="width:30%"></td>
        </tr>
        <tr>
            <th style="width:20%">Tarikh</th>
                <td style="width:30%"><?= $mains->TRKHMULA ? date('d/m/Y', strtotime($mains->TRKHMULA)) : null?></td>
            <th style="width:20%">Masa</th>
            <td style="width:30%"><?= $mains->TRKHMULA ? date('H:i', strtotime($mains->TRKHMULA)) : null?>&nbsp;-&nbsp;<?= $mains->TRKHTAMAT ? date('H:i', strtotime($mains->TRKHTAMAT)) : null?></td>
        </tr>
        
        <tr>
            <tr>
            <th style="width:10%">Pengendali</th>
            <td span="1" style="width:30%;">Bil. Pengendali</td>
            <td span="1" style="width:30%;">Suntikan Pelalian Anti-Tifoid</t>
            <td span="1" style="width:30%;">Kursus Pengendali Makanan</td>
            </tr>
            <tr>
            <td></td>
            <td><?= $mains->PPM_BILPENGENDALI?></td>
            <td><?= $mains->PPM_SUNTIKAN_ANTITIFOID?></td>
            <td><?= $mains->PPM_KURSUS_PENGENDALI?></td>
            </tr>
            <!-- </table> -->
        </tr>

    </table>
    <br>
    <table style="width:100%" >
       
        <tr>
            <td span="1" style="width:15%; font-size:12px"><b>Perkara</td>
            <td span="1" style="width:50%;font-size:12px"><b>Komponen</td>
            <td span="1" style="width:10%;font-size:12px"><b>Markah</td>
            <td span="1" style="width:10%;font-size:12px"><b>Demerit</td>
            <td span="1" style="width:10%;font-size:12px"><b>Catatan</td>
        </tr>

        <tr>
        <?php 
        $i=0;
            if($models){
                // var_dump($models);
                // exit;
                
                //$prevkodperkaraprgn= $model->perkara0->PRGN;
                $currkodperkaraprgn= '';
                foreach($models as $model){
                    echo "<tr>";
                    $i = $i+1;            

                    if($i==1){
                        $prevkodperkaraprgn= $model->perkara0->PRGN;
                        $currkodperkaraprgn= $model->KODPERKARA.'.'.$model->perkara0->PRGN;
                    }else{

                        if($model->perkara0->PRGN == $prevkodperkaraprgn){
                            $currkodperkaraprgn = '';
                        }else{
                            $prevkodperkaraprgn= $model->perkara0->PRGN;
                            $currkodperkaraprgn= $model->KODPERKARA.'.'.$model->perkara0->PRGN;
                        }
                    }

                    echo "<td style=font-size:12px><b>". $currkodperkaraprgn."</td>";
                    echo "<td style=font-size:12px>". $model->komponen0->PRGN. "\n" .$model->prgn0->PRGN. "</td>";
                    echo "<td style=font-size:12px align='right'>". $model->prgn0->MARKAH  ."</td>";
                    echo "<td style=font-size:12px align='right'>". $model->DEMERIT ."</td>";
                    echo "<td style=font-size:12px>". $model->CATATAN ."</td>";
                    echo "</tr>";
                }	
            }							
        ?>        
    </tr>
    <tr>
        <td colspan="2" style="height:30px; font-size:12px; text-align:justify">&nbsp;&nbsp;<b>Jumlah Markah</td>
        <td style="height:20px; width:10% font-size:12px; text-align:right"><b><?=$sum_totalmark?></td>
        <td style="width:10% font-size:12px; text-align:right"><b><?=$sum_demerit?></td>
    </tr>
    <tr>
        <td colspan="4" style="height:30px; font-size:12px; text-align:justify">&nbsp;&nbsp;<b>Purata Pemarkahan</td>
        <td colspan="1" style="font-size:12px; text-align:right"><b><?=$jumlahmarkah?></td>
    </tr>
    </table>
    <br>
    <div style="font-size:12px;" align="left"><br><b>JUMLAH MARKAH (100-Perkara[A+B+C+D+E+F+G])=</b></div>
    <br>
    <div style="font-size:12px;" align="left">ATAU</div>
    <br>
    <div style="font-size:12px;" align="left"><b>JUMLAH MARKAH = [(x-y)/x]x100%&nbsp;=&nbsp;<?=$jumlahmarkah?>&nbsp;%</b></div>
    <br>
    <table style="padding-top:20px;text-align:justify;">
        <tr>
            <td style="font-size:12px; text-align:center"><b>Julat Pemarkahan<br>%</td>
            <td style="font-size:12px; text-align:center"><b>Penarafan</td>
            <td style="font-size:12px; text-align:center"><b>Gred yang Diperolehi</td>
        </tr>
        <tr>
            <td style="font-size:10px; text-align:center">86-100</td>
            <td style="font-size:15px; text-align:center">A</td>
            <td style="font-size:15px; text-align:center"> </td>
            
        </tr>
        <tr>
            <td style="font-size:10px; text-align:center">71-85</td>
            <td style="font-size:15px; text-align:center">B</td>
            <td style="font-size:15px; text-align:center"> </td>
            
        </tr>
        <tr>
            <td style="font-size:10px; text-align:center">51-70</td>
            <td style="font-size:15px; text-align:center">C</td>
            <td style="font-size:15px; text-align:center"> </td>
        </tr>
    </table>
    <br>
    <table style="width:100%" >

        <tr>
            <th style="font-size:10px; width:20%",  align="justify">Markah Keseluruhan</th>
                <td style="font-size:10px; width:30%" align="right"><?=$jumlahmarkah?></td>
            <th style="font-size:10px; width:20%" align="justify">Gred</th>
                <td style="font-size:10px; width:30%" align="right"><?= $gred?></td>
        </tr>
    </table>

    <br><br><br><br>


    <div style="float:left; width:50%;">
        <div>..........................................</div>
        <div>(<?=$mains->ketuapasukan0->pengguna0->NAMA?>)</div>
        <div>Nama dan Tandatangan Pegawai Pemeriksa</div>
    </div>
    
    <div style="float:right; width:50%; ">
    <div>Cop Rasmi Pegawai Pemeriksa</u></div>
        <table>
            <tr>
            <td style=" height:80px; width:8%" ></td></tr>
        </table>
    </div>
    <br><br><br><br>
    <div>
        <div>..........................................</div>
        <div>(<?=$mains->NAMAPENERIMA?>)</div>
        <div>Nama dan Tandatangan Penerima/Saksi</div>
        <div>No.K/P / Pasport:<?=$mains->NOKPPENERIMA?></div>
    </div>
</div>