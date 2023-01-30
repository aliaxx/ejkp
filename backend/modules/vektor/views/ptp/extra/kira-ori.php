<?php

use backend\modules\vektor\utilities\OptionHandler;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use backend\modules\vektor\models\SasaranPtp;

/**
 * @var View $this
 * @var SampelMakanan $bekas
 */

$this->title = $bekas->NOSIRI;
$this->params['breadcrumbs'] = [
    ['label' => 'PTP', 'url' => ['index']],
    $this->title,
    'Pengiraan Pencapaian',
    
];

$bekas->ai = Yii::$app->db->createCommand("SELECT (SUM(JUMPOSITIF)/SUM(PENCAPAIAN))FROM TBSASARAN_PTP 
        WHERE NOSIRI = '$bekas->NOSIRI'")->queryOne();
$ai = implode(' ', $bekas->ai); //convert to string
// $ai1 = floor(($ai*100))/100; //maintain decimal without rounding but will ignore the last number if 0. eg: 0.20=>0.2  
// $ai1 = bcdiv($ai, 1, 2) //truncated value to 2 decimal places


$bekas->bi = Yii::$app->db->createCommand("SELECT (SUM(B.BILPOSITIF)/SUM(A.PENCAPAIAN)) FROM TBSASARAN_PTP A, TBBEKAS_PTP B 
WHERE A.NOSIRI = '$bekas->NOSIRI' AND A.NOSIRI=B.NOSIRI")->queryOne();
$bi = implode(' ', $bekas->bi);
// $bi1 = floor(($bi*100))/100;     

$bekas->ci = Yii::$app->db->createCommand("SELECT (SUM(BILPOSITIF)/SUM(BILBEKAS)) FROM TBBEKAS_PTP 
        WHERE NOSIRI = '$bekas->NOSIRI'")->queryOne();
$ci = implode(' ', $bekas->ci); 
// $ci1 = floor(($ci*100))/100;   

// var_dump($ai1);
// var_dump("<br>");
// var_dump(number_format($bi,2,".","."));
// var_dump("<br>");
// var_dump($bekas->ci);
// exit();
?>

<style>
table, th, td {
  border: 1px solid lightslategray;
  border-collapse: collapse;
}

/* tr {
  border: 1px solid black;
  border-collapse: separate;
} */

th {
  background-color: #B0C4DE;
}  

td:nth-child(1){
  background-color: #DCDCDC;
}  

/* th:nth-child(even),td:nth-child(even) {
  background-color: rgba(150, 212, 212, 0.4);
}  */
</style>


<div>
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['bekas', 'nosiri' => $bekas->NOSIRI], [ //bekas is action -NOR22092022
            'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
            'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
        ]) ?>
    </div>
    <div class="report-master-search">
        <div class="panel panel-default no-print">
        <!-- <div class="panel panel-default no-print" style="width:50%"> -->
            <div class="panel-heading">
                <?= Yii::t('app', 'Pengiraan Pencapaian') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">  
                        <div class="row">
                            <div class="col-md-12">
                            <table id="liputanTable" class="table table-condensed">
                                <thead style="color:#000000">
                                    <tr>
                                        <tr>
                                            <th style="width:30%; text-align:center"><?= Yii::t('app', 'Perkara') ?></th>
                                            <th style="width:10%; text-align:center"><?= Yii::t('app', 'Jumlah') ?></th>
                                        </tr>
                                        <tr>
                                            <td><?= Yii::t('app', '1%Liputan Premis Diperiksa Lengkap (Dalam & Luar Rumah)') ?></td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?= Yii::t('app', 'Indeks Aedes (AI)') ?></td>
                                            <td style="text-align:center">
                                                <b><?= bcdiv($ai, 1, 2) ?></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?= Yii::t('app', 'Indeks Breteau (BI)') ?></td>
                                            <td style="text-align:center">
                                                <b><?= bcdiv($bi, 1, 2) ?></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?= Yii::t('app', 'Indeks Bekas (CI)') ?></td>
                                            <td style="text-align:center"> 
                                                <b><?= bcdiv($ci, 1, 2) ?></b>                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?= Yii::t('app', 'Indeks Purpa (HPI)') ?></td>
                                            <td style="text-align:center">
                                            </td>
                                        </tr>
                                    </tr>
                                </thead>
                                <!-- <tbody> -->
                                    <!-- tbody use to display record -NOR04102022-->
                                <!-- </tbody>             -->
                            </table>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCss("
#liputanTable > thead > tr > th {text-align:center;height:100%}
#liputanTable > tbody > tr > td {text-align:center}
#liputanTable > tfoot > tr > td {font-size:16px;font-weight:bold;background-color:#CCC}
#liputanTable > tfoot > tr > td:first-child {text-align:right}
#liputanTable > tfoot > tr > td:nth-child(2) {text-align:center}
.form-horizontal .form-group {
    margin-right: 0;
    margin-left: 0;
}
");
