<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

use common\utilities\OptionHandler;

use common\utilities\DateTimeHelper;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use backend\modules\lawatanmain\models\LawatanMain;


// $premis = $model->premis;
// $akta = $model->kesalahanakta;

// var_dump ($model->nokmp);
// exit();


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
<div style="font-size:10px;" align="right"><b>No Siri Borang: <?= $model->NOSIRI ?></div>
<div class="print-pdf">
<div style="font-size:18px;" align="center"><b>BORANG PEMERIKSAAN DAN PENGGREDAN </b>
<br><b>PREMIS MAKANAN</b></div>

<break>

 <table style="height:40px; width:100%;">
 <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Lawatan Premis</h5></th></tr>
</table>
<div style="float:left; width:50%;">
 <?= DetailView::widget([
     'model' => $model,
     'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
     'attributes' => [
         
        [
            'label' => 'Nama Pemohon',
            'value' => function ($model){
               return $model->pemilik0->NAMAPEMOHON;
           }
        ], 
        [
            'label' => 'Nama Syarikat',
            'value' => function ($model){
            return $model->pemilik0->NAMASYARIKAT;
        }
        ],
        [
            'label' => 'Alamat Premis',
        //  'attribute' => 'ALAMAT1',
            'value' => function ($model){
            return $model->pemilik0->ALAMAT1.','.$model->pemilik0->ALAMAT2.','.$model->pemilik0->ALAMAT3;
            }
        ],
        [
            'label' => 'Pengendali',
            'format' => 'raw',

            // var_dump($model->kompaun1),
            // exit(),
            'value' => function ($model) {
                if($model->NOSIRI){
                  
                        return GridView::widget([
                            'layout' => '{items}',
                            'model' => $model,
                            'columns' => [
                                [
                                    // 'contentOptions' => ['style' => 'width:10px'],
                                    'label' => 'Bil Pengendali',
                                    'attribute' => 'PPM_BILPENGENDALI',
                                    'value' => function ($model){
                                        return (!empty($model->PPM_BILPENGENDALI))? $model->PPM_BILPENGENDALI: null;
                                    }
                                ],
                                [
                                    
                                    'label' => 'Suntikan Pelalian Anti-Tifoid',
                                    'attribute' => 'trkhbyr',
                                    // 'headerOptions' => ['style' => 'width:5%;'],
                                    // 'contentOptions' => ['style' => 'width:5%;'],
                                    'value' => function ($model){
                                        return (!empty($model->PPM_SUNTIKAN_ANTITIFOID))? $model->PPM_SUNTIKAN_ANTITIFOID: null;
                                    }
                                    
                                ],
                                [
                                    'label' => 'Kursus Pengendali Makanan',
                                    'attribute' => 'noresit',
                                    // 'headerOptions' => ['style' => 'width:5%;'],
                                    // 'contentOptions' => ['style' => 'width:5%;'],
                                    'value' => function ($model){
                                        return (!empty($model->PPM_KURSUS_PENGENDALI))? $model->PPM_KURSUS_PENGENDALI: null;
                                    }
                                ],
                            ],
                        ]);
                    }
            }
        ],
         
     ],            
 ])?>
</div>

<!-- Maklumat Lawatan Premis on right side -->
<!-- <div style="float:left; margin-left:12px; width:50%;"> -->
<div style="float:left; width:50%;">
 <?= DetailView::widget([
     'model' => $model,
     'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
     'attributes' => [
       
        [
            'attribute' => 'NOKPPEMOHON',
            'value' => function ($model){
               return $model->pemilik0->NOKPPEMOHON;
           }
        ], 
        [
            'attribute' => 'NOTEL',
            'value' => function ($model){
                return $model->pemilik0->NOTEL;
            }
        ],
        [
            'attribute' => 'NORUJUKAN',
        ],
        [
            'attribute' => 'TRKHMULA',
        ],
        [
            'attribute' => 'PPM_BILPENGENDALI',
            'value' => function ($model){
                return (!empty($model->PPM_BILPENGENDALI))? $model->PPM_BILPENGENDALI: null;
            }
        ],
        [
            'attribute' => 'PPM_KURSUS_PENGENDALI',
            'value' => function ($model){
                return (!empty($model->PPM_KURSUS_PENGENDALI))? $model->PPM_KURSUS_PENGENDALI: null;
            }
        ],
     ],
 ]) ?>
</div>