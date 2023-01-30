<?php

use backend\modules\lawatanmain\models\LawatanMain;
/* @var $this yii\web\View */

$tahun = date('Y');

$models = LawatanMain::find()->select(['IDMODULE', 'TOTAL' => 'count(*)'])
    ->where(['STATUS' => 1])
    ->andWhere(['between', 'TRKHMULA',$tahun.'/01/01 00:00:00', $tahun.'/12/31 23:59:59'])
    ->groupBy('IDMODULE')->all();

//declare variable
$mutu = 0;
$vektor = 0;
$peniaga = 0;
$premis = 0;

//tambahkan value
foreach ($models as $model) {
        //total value untuk mutu makanan
        if ($model->IDMODULE =='SMM' || $model->IDMODULE == 'SDR' || $model->IDMODULE == 'HSW' || $model->IDMODULE == 'PKK'){
            $mutu = $mutu + $model->TOTAL; 
        }
        //total value untuk pencegahan vektor 
        else if ($model->IDMODULE =='PTS' || $model->IDMODULE == 'SRT' || $model->IDMODULE == 'PTP' || $model->IDMODULE == 'ULV'){
            $vektor = $vektor + $model->TOTAL; 
        }
        //total value peniaga kecil & penjaja
        else if ($model->IDMODULE =='KPN'){
            $peniaga = $peniaga + $model->TOTAL; 
        }
        //total value untuk premis makanan
        else {
            $premis = $premis + $model->TOTAL; 
        }    
}

?>

<div class="border">
  <h3>Aktiviti Tahunan Setiap Unit</h3>
      <div class="row">
        <div class="column">
          <div class="card2">
              <div class="text2"><b>Mutu Makanan</b></div>
                  <div class="p1"><b><?= $mutu ?> </b></div>
                      <div class="col-auto">
                          <i class="fas fa-solid fa-utensils fa-4x icon"></i>
                      </div>
          </div>
        </div>

        <div class="column">
          <div class="card3">
              <div class="text3"><b>Pencegahan Vektor</b></div>
                  <div class="p1"><b><?= $vektor ?></b></div>
                      <div class="col-auto">
                          <i class="fas fa-solid fa-spray-can fa-4x icon"></i>
                      </div>
          </div>
        </div>


        <div class="column">
          <div class="card4">
              <div class="text4"><b>Peniaga Kecil & Penjaja</b></div>
                  <div class="p1"><b><?= $peniaga ?></b></div>
                      <div class="col-auto">
                          <i class="fas fa-solid fa-store fa-4x icon"></i>
                      </div>
          </div>
      </div>

        <div class="column">
          <div class="card1">
              <div class="text1"><b>Premis Makanan</b></div>
                  <div class="p1"><b><?= $premis ?></b></div>
                      <div class="col-auto">
                        <i class="fas fa-solid fa-warehouse fa-4x icon"></i>
                      </div>
          </div>
        </div>
      </div>
</div>

    
