<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\bootstrap\ButtonDropdown;
use backend\modules\peniaga\utilities\OptionHandler;

use kartik\widgets\DatePicker;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;

use backend\models\laporan\KawalanPerniagaanSearch;

/**
 * @var View $this
 * @var KawalanPerniagaanSearch $model
 */

$dropdownActionItems = [];
$dropdownActionItems[] = Html::submitButton('Cetak CSV', [
    'id' => 'action_export-csv', 'name' => 'action[export-csv]',
    'class' => 'btn-dropdown btn btn-default btn-block', 'value' => 1, 'data-pjax' => 0,
]);
$dropdownActionItems[] = Html::submitButton('Cetak PDF', [
    'id' => 'action_export-csv', 'name' => 'action[export-pdf]',
    'class' => 'btn-dropdown btn btn-default btn-block', 'value' => 1, 'data-pjax' => 0,
]);
$jenislaporan = [
    1 => 'Laporan Terperinci Mutu Makanan Berdasarkan Tarikh Lawatan',
    2 => '',
];
?>

<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Carian') ?>
            <span class="pull-right"><i id="toggle-opt" class="fa fa-minus" style="cursor:pointer"></i></span>
        </div>
        <div class="panel-body" id="search-body">
            <div class="row">
                <div class="col-md-12">
                <?php $form = ActiveForm::begin(['method' => 'post', 'id' => 'laporanForm','options'=> ['target' => '_blank']]) ?>
                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'jenislaporan')->radioList($jenislaporan, [
                                'item' => function ($index, $label, $name, $checked, $value) {
                                    return '<div>' . Html::radio($name, $checked, ['value' => $value]) . ' <label class="control-label">' . $label . '</label></div>';
                                }
                            ])->label('Jenis Laporan') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right action-buttons">
                                <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['/laporan/' . Yii::$app->controller->action->id], [
                                    'class' => 'btn btn-default',
                                ]) ?>
                                <?= Html::submitButton('Papar', [
                                    'class' => 'btn btn-primary', 'id' => 'action_quickreport', 'name' => 'action[quickreport]', 
                                ])  ?> 

                                <!-- <?= ButtonDropdown::widget([
                                    'encodeLabel' => false,
                                    'label' => '<i class="glyphicon glyphicon-print"></i> Cetak',
                                    'dropdown' => [
                                        'items' => $dropdownActionItems,
                                    ],
                                    'dropdownClass' => '\common\widgets\Dropdown',
                                    'options' => ['id' => 'printingBtnGroup', 'class' => ['btn btn-primary'], 'title' => 'Cetak', 'aria-label' => 'Cetak'],
                                ]) ?> -->
                            </span>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs("
let searchBody = 0;
function toggleSearchBody(toggle)
{
    if (toggle) {
        searchBody = 0;
        $('#search-body').show(600, function (){
            $('#toggle-opt').toggleClass('fa-plus');
            $('#toggle-opt').toggleClass('fa-minus');
        });
    } else {
        searchBody = 1;
        $('#search-body').hide(600, function (){
            $('#toggle-opt').toggleClass('fa-minus');
            $('#toggle-opt').toggleClass('fa-plus');
        });
    }
}

$(document).ready(function () {
    $('#toggle-opt').click(function () {
        toggleSearchBody(searchBody);
    });
});
");
