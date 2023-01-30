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
            // $d = date_parse_from_format("d/m/Y", $TRKHMULA);
            // $month = $d["month"];

            $query = LawatanMain::find()
                    ->where(['IDSUBUNIT' => $idsubunit]) 
                    ->andWhere(['not', ['LATITUD' => null]])
                    ->andWhere(['not', ['LONGITUD' => null]])
                    ->andWhere(["TO_CHAR(TRUNC(TRKHMULA,'MM'),'MM')"=> $monthval])
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
    
</head>
<body onload="initialize()">
<div class="border">
  <h3>Paparan Lokasi bagi Unit <?= $label ?></h3>
        <div class="row">
            <div class="column">
              <!-- <select name="Months" id="dropdown-months">
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
              </select> -->

              <select>
                  <option value="">SELECT MONTH</option>
                  <?php
                  for ($i = 0; $i < 12; $i++) 
                  {
                      $getMonth = strtotime(sprintf('%d months', $i));   
                      $monthLabel = date('F', $getMonth)."-".date("Y");
                      $monthval = date("Y")."-".date('m', $getMonth); ?>
                      <option value="<?php echo $monthval; ?>"><?php echo $monthLabel; ?></option>
                  <?php } 
                  ?>
              </select>
        
              <br><br><br>
                <div id="map-canvas" style="width: 1500px; height: 600px;"></div>
            </div>
        </div>

   
<div> 

</body>
</html>