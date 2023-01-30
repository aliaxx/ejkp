<?php

// use backend\modules\datakes\models\Datakes;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;


/**
 * @var View $this
 * @var SasaranSrt $model
 * @var ActiveForm $form
 */

 
?>

<style>
.tg  {
    border-collapse:collapse;
    border-spacing:0;
}

.tg td{
    border-color:black;
    border-style:solid;
    border-width:1px;
    font-family:Arial, sans-serif;
    font-size:14px;
    overflow:hidden;
    padding:10px 5px;
    word-break:normal;
}

.tg th{
    border-color:black;
    border-style:solid;
    border-width:1px;
    font-family:Arial, 
    sans-serif;
    font-size:14px;
    font-weight:normal;
    overflow:hidden;
    padding:10px 5px;
    word-break:normal;
}

.tg .tg-g7sd{
    border-color:inherit;
    font-weight:bold;
    text-align:left;
    vertical-align:middle
}

.tg .tg-uzvj{
    border-color:inherit;
    font-weight:bold;
    text-align:center;
    vertical-align:middle
}

.tg .tg-yla0{
    font-weight:bold;
    text-align:left;
    vertical-align:middle
}

.tg .tg-7btt{
    border-color:inherit;
    font-weight:bold;
    text-align:center;
    vertical-align:top
}

.tg .tg-fymr{
    border-color:inherit;
    font-weight:bold;
    text-align:left;
    vertical-align:top
}
</style>

<div class="row">
    <!-- <div class="col-md-offset-12 col-md-12"> col-md-offset-12 cause table to drag to the right -NOR30092022 -->
    <div class="col-md-12">
        <label style="color:#FF0000";>Liputan Semburan * : <button id="btnAddItem" type="button" class="btn btn-success waves-effect"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah') ?></button></label>
        <table id="liputanTable" class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <td class="tg-g7sd" rowspan="3">Jenis Premis</td>
                    <td class="tg-uzvj" colspan="4">Linkungan Jarak Dari Premis Kes<br>Sekiranya Operasi Kawalan Kes/Wabak</td>
                    <!-- <td class="tg-g7sd" colspan="3" rowspan="2">Jumlah Keseluruhan</td> -->
                    <td class="tg-yla0" rowspan="3">Hapus</td>
                </tr>
                <tr>
                    <td class="tg-7btt" colspan="2">Dalam Linkungan<br>400M</td>
                    <td class="tg-7btt" colspan="2">&gt; 400M</td>
                </tr>
                <tr>
                    <td class="tg-7btt">Sasaran Premis</td>
                    <td class="tg-7btt">Pencapaian Premis<br>Disembur</td>
                    <td class="tg-7btt">Sasaran Premis</td>
                    <td class="tg-7btt">Pencapaian Premis <br>Disembur</td>
                    <!-- <td class="tg-fymr">Sasaran Premis</td>
                    <td class="tg-7btt">Pencapaian Premis<br>Disembur</td>
                    <td class="tg-fymr">% Pencapaian</td> -->
                </tr>            
            </thead>
            <tbody>
                <!-- tbody use to display record -NOR04102022-->
            </tbody>            
        </table>
    </div>
</div>
<?php
$this->registerCss("
#liputanTable > thead > tr > th {text-align:center}
#liputanTable > tbody > tr > td {text-align:center}
#liputanTable > tfoot > tr > td {font-size:16px;font-weight:bold;background-color:#CCC}
#liputanTable > tfoot > tr > td:first-child {text-align:right}
#liputanTable > tfoot > tr > td:nth-child(2) {text-align:center}
.form-horizontal .form-group {
    margin-right: 0;
    margin-left: 0;
}
");

$this->registerJs("
var itemCounter = 1;

function getLiputanForm(initialize)
{
    // alert(initialize);
    // alert(itemCounter); 


    let urlItemForm = '" . Url::to(['/vektor/ulv/get-liputan-form']) . "';

    $.get(urlItemForm + '?counter=' + itemCounter + '&initialize=' + initialize, function (data) {
        $('#liputanTable tbody').append(data);
    });
}

$(document).ready(function () {
    $('#btnAddItem').click(function () {
        // alert('test');
        getLiputanForm(0);
        itemCounter++;
    });

    $('#sasaranulv-form').on('click', 'button[id^=\"btnRemoveRow_\"]', function () {
        // alert('test');
        let rowToRemove = this.id.split('_').pop();
        $('#itemRow_' + rowToRemove).remove();
    });

});
", View::POS_END);



if (!$liputan->premis) {   
    $this->registerJs("
    $(document).ready(function () {
        getLiputanForm(0);
        itemCounter++;
    });
    ", View::POS_END);
} else {
    // var_dump($liputan);
    // var_dump($liputan->NOSIRI);
    // exit();
    $this->registerJs("
    $(document).ready(function () {
        itemCounter = " . (count($liputan->premis) + 1) . ";
        getLiputanForm('" . $liputan->NOSIRI . "');
    });
    ", View::POS_END);
    // var_dump('itemCounter');
    // exit();
}
