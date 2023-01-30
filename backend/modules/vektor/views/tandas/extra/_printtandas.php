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

if($model->transtandasrec ){
    // var_dump($model->KATPREMIS );
    $jumlahmarkah = $model->markahtandas['total'];
    $gred = $model->markahtandas['gred'];
    $sum = $model->markahtandas['sum'];
    $sum1 = $model->markahtandas['sum1'];
    $sum2 = $model->markahtandas['sum2'];
    $sum3 = $model->markahtandas['sum3'];
    $sum4 = $model->markahtandas['sum4'];

}else {
    // var_dump("hahhah");
    // exit();
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

// var_dump($model);
// exit;

?>

<style>
    table {
    margin: auto;
    border-collapse: collapse;
    width: 100%;
}

/* border visible */
table, td, tr { 
  border: 1px solid black;
  /* border:0px solid transparent;} */
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
<div style="font-size:10px;" align="right"><b>No Siri Borang: <?= $model->NOSIRI ?></div>
<div class="print-pdf">
<div style="font-size:18px;" align="center"><b>BORANG PEMERIKSAAN DAN PENGGREDAN </b>
<br><b>KEBERSIHAN TANDAS AWAM</b></div>

<break>

<div style="padding-top:20px;text-align:justify;">
    <!-- <table style="width:100%" > -->
    <table cellpadding="0" cellspacing="0" border="0">
       
        <tr>
            <th style="width:20%">Kategori</th>
                <td style="width:30%"><?= $model->katpremis0->PRGN?></td>
            <th style="width:20%">No. Tel</th>
                <td style="width:30%"><?= $pemiliks->NOTEL?></td>
        </tr>
        <tr>
            <th style="width:20%">Nama Syarikat</th>
                <td style="width:30%"><?= $pemiliks->NAMASYARIKAT?></td>
            <th style="width:20%">Alamat Premis</th>
                <td style="width:30%"><?= $pemiliks->ALAMAT1.','.$pemiliks->ALAMAT2.','.$pemiliks->ALAMAT3?>
                <br><?= $pemiliks->POSKOD?></td>
        </tr>
        <tr>
            <th style="width:20%">Tarikh</th>
                <td style="width:30%"><?= $mains->TRKHMULA ? date('d/m/Y', strtotime($mains->TRKHMULA)) : null?></td>
            <th style="width:20%">Masa</th>
                <td style="width:30%"><?= $mains->TRKHMULA ? date('H:i', strtotime($mains->TRKHMULA)) : null?>&nbsp;-&nbsp;<?= $mains->TRKHTAMAT ? date('H:i', strtotime($mains->TRKHTAMAT)) : null?></td>
        </tr>
        

    </table>
    <table>
        <tr>
            <th style="width:20%">No. Lesen</th>
                <td style="width:80%"><?= $pemiliks->NOLESEN?></td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td>&nbsp;1=Amat Lemah&nbsp;&nbsp;&nbsp;2=Lemah&nbsp;&nbsp;&nbsp;3=Sederhana&nbsp;&nbsp;&nbsp;4=Memuaskan&nbsp;&nbsp;&nbsp;5=Baik&nbsp;&nbsp;&nbsp;6=Sangat Baik</td>
        </tr>
    </table>
    <br>
    <table style="width:100%" >
        <thead>
            <!-- <tr>
                <td style= "width:6%"><b>Bil</td>
                <td style="height:50px; width:20%" align="center"><b>Kriteria</td>
                <td rowspan="2" style="height:50px; width:20%" align="center">Kriteria</td>
                <td colspan="3" style="font-size:14px;text-align:center">Markah</td>
                
            </tr> -->

            <tr>
                <td rowspan="2" style= "width:6%"><b>Bil</td>
                <td rowspan="2" style="height:50px; width:15%" align="center"><b>Kriteria</td>
                <td colspan="6" style="font-size:14px;text-align:center"><b>Markah</td>
            </tr>
            <tr>
                <td style="font-size:14px; width:18%"><b>Komponen</td>
                <td style="font-size:14px;"></td>
                <td style="font-size:14px;"><b>Lelaki</td>
                <td style="font-size:14px;"><b>Wanita</td>
                <td style="font-size:14px;"><b>OKU</td>
                <td style="font-size:14px;"><b>Unisex</td>
                <td style="font-size:14px;"><b>Kanak-Kanak</td>
            </tr>
        </thead>


        <tr>
        <?php 
        $i=0;
            if($models){
                
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

                        // if($i==1){
                        //     $prevkodperkaraprgn= $model->perkara0->PRGN;
                        //     $currkodperkaraprgn= $model->KODPERKARA.'.'.$model->perkara0->PRGN;
                        //     echo "<td><b>". $currkodperkaraprgn."</td>";
                        // }else{

                        //     if($model->perkara0->PRGN == $prevkodperkaraprgn){
                        //         $currkodperkaraprgn = $model->komponen0->PRGN;
                        //         echo "<td>". $currkodperkaraprgn."</td>";
                        //     }else{
                        //         $prevkodperkaraprgn= $model->perkara0->PRGN;
                        //         $currkodperkaraprgn= $model->KODPERKARA.'.'.$model->perkara0->PRGN;
                        //         echo "<td><b>". $currkodperkaraprgn."</td>";
                        //     }
                        // }

                    echo "<td>".$i."."."</td>";
                    
                    echo "<td><b>". $currkodperkaraprgn."</td>";
                    echo "<td>". $model->komponen0->PRGN."</td>";
                    // echo "<td>". $model->komponen0->PRGN."</td>";
                    echo "<td align='right'>". $model->prgn0->MARKAH  ."</td>";
                    echo "<td align='right'>". $model->ML  ."</td>";
                    echo "<td align='right'>". $model->MW  ."</td>";
                    echo "<td align='right'>". $model->MO  ."</td>";
                    echo "<td align='right'>". $model->MU  ."</td>";
                    echo "<td align='right'>". $model->MK  ."</td>";
                   
                    echo "</tr>";
                   
                }
            }							
        ?> 
        </tr>
        <tr>
            <td colspan="3" style="height:30px; font-size:15px; text-align:justify">&nbsp;&nbsp;<b>Jumlah Markah</td>
            <td style="height:20px; width:10% font-size:15px; text-align:right"><b>100</td>
            <td style="height:20px; width:10% font-size:15px; text-align:right"><b><?=$sum?></td>
            <td style="width:10% font-size:15px; text-align:right"><b><?=$sum1?></td>
            <td style="width:10% font-size:15px; text-align:right"><b><?=$sum2?></td>
            <td style="width:10% font-size:15px; text-align:right"><b><?=$sum3?></td>
            <td style="width:10% font-size:15px; text-align:right"><b><?=$sum4?></td>
        </tr>
        <tr>
            <td colspan="3" style="height:30px; font-size:15px; text-align:justify">&nbsp;&nbsp;<b>Purata Pemarkahan</td>
            <td colspan="6" style="font-size:15px; text-align:right"><b><?=$jumlahmarkah?></td>
        </tr>
        <tr>
            <td colspan="3" style="height:30px; font-size:15px; text-align:justify">&nbsp;&nbsp;<b>Bintang</td>
            <td colspan="6" style="font-size:18px; text-align:right"><b><?=$gred?></td>

            <!-- <td colspan="3" style="font-size:15px; text-align:right"><b>Bintang</td>
            <td colspan="6" style="font-size:15px; height:20px; width:15%" align="center"><b><?=$gred?></td> -->
        </tr>
    </table>
    <div style="font-size:12px;" align="left"><br><b>PENARAFAN BINTANG TAHAP KEBERSIHAN TANDAS AWAM</b></div>
    <br>
    <table style="padding-top:20px;text-align:justify;">
        <tr>
            <td style="font-size:12px; text-align:center"><b>Pemarkahan</td>
            <td style="font-size:12px; text-align:center"><b>Bintang</td>
        </tr>
        <tr>
            <td style="font-size:10px; text-align:center">91-100</td>
            <td style="font-size:15px; text-align:center">* * * * *</td>
            
        </tr>
        <tr>
            <td style="font-size:10px; text-align:center">81-90</td>
            <td style="font-size:15px; text-align:center">* * * * *</td>
            
        </tr>
        <tr>
            <td style="font-size:10px; text-align:center">71-80</td>
            <td style="font-size:15px; text-align:center">* * *</td>
        </tr>
        <tr>
            <td style="font-size:10px; text-align:center">61-70</td>
            <td style="font-size:15px; text-align:center">* *</td>
            
            
        </tr>
        <tr>
            <td style="font-size:10px; text-align:center">51-60</td>
            <td style="font-size:15px; text-align:center">*</td>
        </tr>
        <tr>
            <td style="font-size:10px; text-align:center">50 KE BAWAH</td>
            <td style="font-size:10px; text-align:center">TIADA BINTANG (NOTIS, KOMPAUN DIBERIKAN)</td>
        </tr>
    </table>
    <br><br>

    <div style="font-size:10px;" align="left"><b>Catatan: </b><?= $mains->CATATAN?></div>

    <br><br><br><br>


    <div style="float:left; width:50%;">
        <div>..........................................</div>
        <div>(<?=$mains->createdByUser->NAMA?>)</div>
        <div>Nama dan Tandatangan Pegawai Pemeriksa</div>
        <br>
        <div><U>Cop Rasmi Pegawai Pemeriksaan</div>

        <table>
            <tr>
                <td style=" height:80px; width:8%" ></td></tr>
        </table>

    </div>
    <div style="float:right; width:50%; ">
        <div>..........................................</div>
        <div>(<?=$mains->NAMAPENERIMA?>)</div>
        <div>Nama dan Tandatangan Penerima/Saksi</div>
        <div>No.K/P / Pasport:<?=$mains->NOKPPENERIMA?></div>
</div>