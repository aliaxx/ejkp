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
 * @var SasaranLvc $model
 * @var ActiveForm $form
 */

 
?>
<div class="row">
    <!-- <div class="col-md-offset-12 col-md-12"> col-md-offset-12 cause table to drag to the right -NOR30092022 -->
    <div class="col-md-12">
        <label style="color:#FF0000";>Sasaran Premis * : <button id="btnAddItem" type="button" class="btn btn-success waves-effect"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah') ?></button></label>
        <table id="sasaranTable" class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <tr>
                        <th><?= Yii::t('app', 'Jenis Premis') ?></th>
                        <th><?= Yii::t('app', 'Sasaran (50 M)') ?></th>
                        <th><?= Yii::t('app', 'Pencapaian') ?></th>
                        <th><?= Yii::t('app', 'Peratusan (%)') ?></th>
                        <th><?= Yii::t('app', 'Jumlah Positif') ?></th>
                        <th><?= Yii::t('app', 'Hapus')?></th>
                    </tr>
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
#sasaranTable > thead > tr > th {text-align:center}
#sasaranTable > tbody > tr > td {text-align:center}
#sasaranTable > tfoot > tr > td {font-size:16px;font-weight:bold;background-color:#CCC}
#sasaranTable > tfoot > tr > td:first-child {text-align:right}
#sasaranTable > tfoot > tr > td:nth-child(2) {text-align:center}
.form-horizontal .form-group {
    margin-right: 0;
    margin-left: 0;
}
");

$this->registerJs("
var itemCounter = 1;

function getSasaranForm(initialize)
{
    // alert(initialize);
    // alert(itemCounter); 


    let urlItemForm = '" . Url::to(['/vektor/lvc/get-sasaran-form']) . "';

    $.get(urlItemForm + '?counter=' + itemCounter + '&initialize=' + initialize, function (data) {
        $('#sasaranTable tbody').append(data);
    });
}

$(document).ready(function () {
    $('#btnAddItem').click(function () {
        // alert('test');
        getSasaranForm(0);
        itemCounter++;
    });

    $('#sasaranlvc-form').on('click', 'button[id^=\"btnRemoveRow_\"]', function () {
        // alert('test');
        let rowToRemove = this.id.split('_').pop();
        $('#itemRow_' + rowToRemove).remove();
    });

});
", View::POS_END);



if (!$sasaran->premis) {   
    $this->registerJs("
    $(document).ready(function () {
        getSasaranForm(0);
        itemCounter++;
    });
    ", View::POS_END);
} else {
    // var_dump($sasaran);
    // var_dump($sasaran->NOSIRI);
    // exit();
    $this->registerJs("
    $(document).ready(function () {
        itemCounter = " . (count($sasaran->premis) + 1) . ";
        getSasaranForm('" . $sasaran->NOSIRI . "');
    });
    ", View::POS_END);
    // var_dump('itemCounter');
    // exit();
}
