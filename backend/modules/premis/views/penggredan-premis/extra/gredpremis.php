<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html\activeCheckboxList;    //zihan
/**
 * @var View $this
 * @var Transkolam $gredpremis
 * @var ActiveForm $form
 */

$this->title = 'Penggredan Premis';
$this->params['breadcrumbs'] = [
    'Penggredan Premis Makanan',
    ['label' => 'Penggredan Premis', 'url' => ['index']],
    $this->title,
];
// var_dump($gredpremis->transpremisrec);
// exit();
if($gredpremis->transpremisrec){
    // var_dump($gredpremis->NOSIRI );
    $jumlahmarkah = $gredpremis->markahpremis['sum'];
    $gred = $gredpremis->markahpremis['gred'];
}else {
    // var_dump("hahhah");
    // exit();
    $jumlahmarkah = '';
    $gred = '';
}

// var_dump($gredpremis->pemilik0);
// exit();
?>




<div>
    <table align="right">
        <td>
            <?php $form = ActiveForm::begin(['method' => 'post', 'id' => 'viewForm','options'=> ['target' => '_blank']]); ?>
            <?= Html::submitButton('<i class="glyphicon glyphicon-print"></i> Cetak', [
                        'class' => 'btn btn-primary', 'id' => 'action_export-pdf', 'name' => 'action[export-pdf]',
                    ])  ?>
            <?php ActiveForm::end(); ?>
        </td>
    </table>
    
    <?php $form = ActiveForm::begin([
        'id' => 'transpremis-form', //ID TU REFER NAMA MODEL-TENGOK DEKAT F12 NAK CONFIRM (alia-080922)
    ]); ?>

    <?= \codetitan\widgets\ActionBar::widget([
        'permissions' => ['save2close' => Yii::$app->access->can('PPM-write')],
    ]) ?>

    <div style="margin-top:61px;"></div>

    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>

<br>
<?php if($gredpremis->transpremisrec) : ?>
    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Keputusan Pemeriksaan') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-2">
                        <?= $form->field($gredpremis, 'jumlahmarkah')->textInput(['value' => $jumlahmarkah])->label('Jumlah Markah')?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($gredpremis, 'gred')->textInput(['value' => $gred])?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="report-master-search">   
    
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Pemarkahan* :') ?>
        </div>
    <!-- table title -->
    <!-- <h4>Pemarkahan* :  </h4> -->
        <div class="panel-body">
            <table id="premisTable" class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <!-- <th width=2%><?= Yii::t('app', 'Pilih') ?></th> -->
                            <!-- <th width=5%><input type="checkbox" id='checkall' />Pilih Semua</th> -->
                            <th width=2%><?= Yii::t('app', 'No') ?></th>
                            <th width=15%><?= Yii::t('app', 'Perkara') ?></th>
                            <th width=25%><?= Yii::t('app', 'Komponen') ?></th>
                            <th width=30%><?= Yii::t('app', 'Penerangan') ?></th>
                            <th width=5%><?= Yii::t('app', 'Markah') ?></th>
                            <th width=5%><?= Yii::t('app', 'Demerit') ?></th>
                            <th width=10%><?= Yii::t('app', 'Catatan') ?></th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>        
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <span class="pull-right">              
            <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1, 
                'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
            ]) ?>
        </span>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerCss("
#premisTable > thead > tr > th {text-align:center}
#premisTable > tbody > tr > td {text-align:left}
#premisTable > tfoot > tr > td {font-size:16px;font-weight:bold;background-color:#CCC}
#premisTable > tfoot > tr > td:first-child {text-align:right}
#premisTable > tfoot > tr > td:nth-child(2) {text-align:center}
.form-horizontal .form-group {
    margin-right: 0;
    margin-left: 0;
}
");

$this->registerJs("
//var itemCounter = 1;
var itemCounter = 0;

//function getItemForm(initialize)
function getItemForm(initialize, checkall)
{
    // alert(initialize);
    let urlItemForm = '" . Url::to(['/premis/penggredan-premis/get-item-form']) . "';
    //$.get(urlItemForm + '?counter=' + itemCounter + '&initialize=' + initialize, function (data) {
        $.get(urlItemForm + '?counter=' + itemCounter + '&initialize=' + initialize  + '&checkall=' + checkall, function (data) {
        $('#premisTable tbody').append(data);
    });
}

$(document).ready(function () {

    // // Check or Uncheck All checkboxes  //zihan
    // $('#checkall').change(function(){
       
    //     var checked = $(this).is(':checked');

    //     alert(checked);


    //     // itemCounter = " . (count($gredpremis->transpremisrec) + 1) . ";

    //     // alert('" . $gredpremis->NOSIRI . "');
    //     // //getItemForm('" . $gredpremis->NOSIRI . "'); 

    //     getItemForm('" . $gredpremis->NOSIRI . "', checked); 

    // });
    
});
", View::POS_END);
if (!$gredpremis->transpremisrec) {
    $this->registerJs("
    $(document).ready(function () {
        getItemForm(0, true);
        itemCounter++;
    });
    ", View::POS_END);
} else {
    $this->registerJs("
    $(document).ready(function () {
        itemCounter = " . (count($gredpremis->transpremisrec) + 1) . ";
        getItemForm('" . $gredpremis->NOSIRI . "', false); 
    });
    ", View::POS_END);
}

