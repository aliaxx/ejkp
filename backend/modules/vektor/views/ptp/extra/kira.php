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

?>

<style>
table, td, tr {
  border: 1px solid black;
  border-collapse: separate;
}

/* tr {
  border: 1px solid black;
  border-collapse: separate;
} */

/* th {
  background-color: #B0C4DE;
}   */

/* td:nth-child(1){
  background-color: #DCDCDC;
}   */

/* th:nth-child(even),td:nth-child(even) {
  background-color: rgba(150, 212, 212, 0.4);
}  */
</style>


<div>
    <table id="liputanTable" class="table table-condensed">
        <thead style="color:#000000">
            <tr>
                <tr>
                    <td style="width:30%; text-align:center; border: 1px solid black"><?= Yii::t('app', 'Perkara') ?></td>
                    <td style="width:10%; text-align:center; border: 1px solid black"><?= Yii::t('app', 'Jumlah') ?></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app', '1%Liputan Premis Diperiksa Lengkap (Dalam & Luar Rumah)') ?></td>
                    <td>
                        <!-- tbc formula -->
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
                        <!-- tbc formula -->
                    </td>
                </tr>
            </tr>
        </thead>
        <!-- <tbody> -->
            <!-- tbody use to display record -NOR04102022-->
        <!-- </tbody>             -->
    </table>   
</div>

<?php
$this->registerCss("
#liputanTable > thead > tr > th {text-align:center;height:100%}
#liputanTable > tbody > tr > td {text-align:center}
// #liputanTable > tfoot > tr > td {font-size:16px;font-weight:bold;background-color:#CCC}
#liputanTable > tfoot > tr > td:first-child {text-align:right}
#liputanTable > tfoot > tr > td:nth-child(2) {text-align:center}
.form-horizontal .form-group {
    margin-right: 0;
    margin-left: 0;
}
");
