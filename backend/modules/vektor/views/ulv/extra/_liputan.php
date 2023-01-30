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
 * @var liputanSrt $model
 * @var ActiveForm $form
 */

 
?>
<div class="row">
    <!-- <div class="col-md-offset-12 col-md-12"> col-md-offset-12 cause table to drag to the right -NOR30092022 -->
    <div class="col-md-12">
        <label style="color:#FF0000";>Liputan Premis * : <button id="btnAddItem" type="button" class="btn btn-success waves-effect"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah') ?></button></label>
        <!-- <table id="liputanTable" class="table table-condensed table-bordered" style="table-layout:fixed;width:100%"> -->
        <table id="liputanTable" class="table table-condensed table-bordered" style="table-layout:fixed;">
            <thead>
                <tr>
                    <!-- <th style="width:25%"><?= Yii::t('app', 'Jenis Premis') ?></th>
                    <th style="width:20%"><?= Yii::t('app', 'Sasaran Premis (400 M)') ?></th>
                    <th style="width:20%"><?= Yii::t('app', 'Pencapaian Premis (400 M)') ?></th>
                    <th style="width:20%"><?= Yii::t('app', 'Sasaran Premis (> 400 M)') ?></th>
                    <th style="width:20%"><?= Yii::t('app', 'Pencapaian Premis (> 400 M)') ?></th>
                    <th style="width:5%"><?= Yii::t('app', 'Hapus')?></th> -->
                    <th style="width:200px"><?= Yii::t('app', 'Jenis Premis') ?></th>
                    <th style="width:150px"><?= Yii::t('app', 'Sasaran Premis (400 M)') ?></th>
                    <th style="width:150px"><?= Yii::t('app', 'Pencapaian Premis (400 M)') ?></th>
                    <th style="width:150px"><?= Yii::t('app', 'Sasaran Premis (> 400 M)') ?></th>
                    <th style="width:150px"><?= Yii::t('app', 'Pencapaian Premis (> 400 M)') ?></th>
                    <th style="width:50px"><?= Yii::t('app', 'Hapus')?></th>
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

    // sasaranulv is model -NOR11102022
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
