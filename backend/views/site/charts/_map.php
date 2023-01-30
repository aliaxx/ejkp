<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use backend\modules\lawatanmain\models\LawatanMain;

//to display subunit
$user = Yii::$app->user->identity;
$label = $user->unit->PRGN;  
$idsubunit = $user->subunit0->ID;
// $date = 
// var_dump($idsubunit);
// exit;
// var_dump($label);
// exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/ilmudetil.css">
    <script src='assets/js/jquery-1.10.1.min.js'></script>       
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://maps.google.com/maps/api/js?sensor=false"></script>
</head>



<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <h3>Paparan Lokasi bagi Unit <?= $label ?></h3>
            <span class="pull-right"><i id="toggle-opt" class="fa fa-minus" style="cursor:pointer"></i></span>
        </div>
        <div class="panel-body" id="search-body">
            <div class="row">
                <div class="col-md-12">
                    <?php $form = ActiveForm::begin(['method' => 'post', 'id' => 'laporanForm','options'=> ['target' => '_blank']]) ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, 'TRKHMULA')->widget(DatePicker::className(), [
                                    'options' => ['autocomplete' => 'off'],
                                    'pluginOptions' => [
                                        'minuteStep' => 1,
                                        'autoclose' => true,
                                        'todayHighlight' => true,
                                        'format' => 'dd/mm/yyyy',
                                        'showMeridian' => true,
                                    ],
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'TRKHTAMAT')->widget(DatePicker::className(), [
                                    'options' => ['autocomplete' => 'off'],
                                    'pluginOptions' => [
                                        'minuteStep' => 1,
                                        'autoclose' => true,
                                        'todayHighlight' => true,
                                        'format' => 'dd/mm/yyyy',
                                        'showMeridian' => true,
                                    ],
                                ]) ?>
                            </div>
                    
                            <div class="col-md-4" style="margin-top:23px">
                                <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                                  'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 0,
                                  'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
                                ]) ?>  
                            </div> 
            
                    <br><br><br>
                        <div id="map-canvas" style="width: 1500px; height: 600px;"></div>
                </div>   
        </div>
    <div>
      <?php ActiveForm::end() ?>
      
<!-- script to display the map -->
    <script>  
      var marker;
        function initialize() {
          var infoWindow = new google.maps.InfoWindow;
          
          var mapOptions = {
            mapTypeId: google.maps.MapTypeId.ROADMAP
          } 
  
          var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
          var bounds = new google.maps.LatLngBounds();

          // Retrieve data from database
          <?php
              $d = date_parse_from_format("d/m/Y", $TRKHMULA);
              $month = $d["month"];

              echo $month;
              // exit;

              $query = LawatanMain::find()
                      ->where(['IDSUBUNIT' => $idsubunit]) 
                      ->andWhere(['not', ['LATITUD' => null]])
                      ->andWhere(['not', ['LONGITUD' => null]])
                      ->andWhere(["TO_CHAR(TRUNC(TRKHMULA,'MM'),'MM')"=> $month])
                      ->all();
        
              foreach ($query as $data) { 

                  $nosiri = $data['NOSIRI'];
                  $lat = $data['LATITUD'];
                  $long = $data['LONGITUD'];

                  echo("addMarker($lat, $long, '<b>$nosiri</b>');\n");
              }

            ?>
          
          // Proses of making marker 
          function addMarker(lat, lng, info) {
              var lokasi = new google.maps.LatLng(lat, lng);
              bounds.extend(lokasi);
              var marker = new google.maps.Marker({
                  map: map,
                  position: lokasi
              });       
              map.fitBounds(bounds);
              bindInfoWindow(marker, map, infoWindow, info);
          }
          
          // Displays information on markers that are clicked
          function bindInfoWindow(marker, map, infoWindow, html) {
            google.maps.event.addListener(marker, 'click', function() {
              infoWindow.setContent(html);
              infoWindow.open(map, marker);
            });
          }
  
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    
    </script>
</html>