<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\ButtonDropdown;

use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;

use backend\modules\peniaga\utilities\OptionHandler;
use backend\models\laporan\LaporanSearch;

/**
 * @var View $this
 * @var LaporanSearch $model
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

//Get data lokasi penjaja KPN
$data['penjaja'] = ['',''];
$source = Yii::$app->db->createCommand("SELECT * FROM C##ESEWA.V_LOCATION_LIST")->queryAll();
$data['penjaja'] = ArrayHelper::map($source, 'LOCATION_ID', 'LOCATION_NAME');

$data['NOPETAK'] = [];

$jenislaporan = [
    1 => '1. Laporan Terperinci Pemantauan Penjaja Berdasarkan Tarikh Lawatan',
    2 => '2. Laporan Bulanan Pemantauan Penjaja Berdasarkan Tarikh Lawatan',
    3 => '2. Laporan Penggerai Ada Berniaga/Tidak Berniaga Langsung/Sendiri Berdasarkan Tarikh Lawatan',
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
                                'options' => [
                                    'autocomplete' => 'off',
                                    'id' => 'trkhmula',
                                ],
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
                    <div class ="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'IDLOKASI')->widget(Select2::className(), [
                                'data' => $data['penjaja'],
                                'options' => [
                                    'placeholder' => '',
                                    'id' => 'idlokasi',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOPETAK')->widget(DepDrop::classname(), [
                                'type' => DepDrop::TYPE_SELECT2,
                                'data' => $data['NOPETAK'],
                                'options' => [
                                    'placeholder' => '',
                                    'id' => 'nopetak',
                                ],
                                'pluginOptions'=>[
                                    'depends'=>['idlokasi'],
                                    'allowClear' => true,
                                    'url'=>Url::to(['/laporan/gerai'])
                                ]
                                ])->label('No Petak') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                                <?= $form->field($model, 'PGNDAFTAR')->dropDownList([], [
                                    'multiple' => true,
                                    ])->label('Nama Pegawai') ?>
                                <?= $form->field($model, '_inputpgndaftar')->hiddenInput()->label(false) ?>
                        </div>
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
                                    'class' => 'btn btn-primary', 'id' => 'action_laporan', 'name' => 'action[laporan]', 
                                ])  ?> 
                                <!-- <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Papar', ['/laporan/get-laporan-peniaga', 'jenislaporan' => $model->jenislaporan, 'TRKHMULA' => $model->TRKHMULA, 'TRKHTAMAT' => $model->TRKHTAMAT, 'IDLOKASI' => $model->IDLOKASI, 'NOPETAK' => $model->NOPETAK], ['class'=>'btn btn-primary'],
                                ['id' => 'action_laporan', 'name' => 'action[laporan]']) ?> -->

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

$this->registerCssFile(Yii::getAlias('@web/css/multi.css'));
$this->registerJsFile(Yii::getAlias('@web/js/multi_faeezrev_v2.js'), ['depends' => 'yii\web\YiiAsset']);
$this->registerJsFile(Yii::getAlias('@web/js/fillSelect.js'));

$this->registerJs("

var _MultiSelectPegawai;

// hold selected Pegawai option elements
var selectedPegawai = [];

// hold selected Pegawai value
var pegawaiValues = [];

var actionSelect = $('#laporansearch-pgndaftar');

actionSelect.multi({
    search_placeholder: 'Cari...',
    multiSelected: selectedPegawai
});

function getnamaPegawai()
{
    $.get('" . Url::to(['/laporan/get-pegawai']) ."', function (data) {
        
        if (data.success) {

            var form = $('#laporan-form');
            selectedPegawai = form.find('option:selected').toArray();

            pegawaiValues = $('#laporan-pgndaftar option:selected').map(function() {  
                return this.value;
            }).get();

            // alert (this.value);

            $.each(data.results, function(key, item) {
                    var o = new Option(item, key);
                    $(o).html(item);
                    actionSelect.append(o);

                    // alert (data.results);
            });
            actionSelect.trigger('change');

        }
    });
}

$(document).ready(function () {

    $('#laporan').submit(function(event){
        $('#laporan-_inputpgndaftar').val(_MultiSelectPegawai);
        return true;
    });

    getnamaPegawai();

});
", View::POS_END);

if ($model->PGNDAFTAR) {

    $this->registerJs("
    
    // hold value to see if pgndaftar attribute has value
     var pegawaiRecords = '" . json_encode($model->PGNDAFTAR) . "';

    $(document).ready(function () {
        // convert pgndaftar into string
        var pgndaftar = '" . implode(',', $model->PGNDAFTAR) . "';
        // convert string pgndaftar into array for js
        var tmpAgensiData = pgndaftar.split(',');

        // predefinded variable
        var arrData = [];
        var arrDataInit = [];
        
        // loop  array
        $.each(tmpAgensiData, function (i, val) {
            // array for selected pgndaftar dropdown list
            arrData[i] = val;
            
            // array for hidden selected pgndaftar dropdown list
            arrDataInit[i] = parseInt(val);
        });

        _MultiSelectAgensi = arrData;
        
        window.setTimeout(function () {
            pegawaiRecords = $.parseJSON(pegawaiRecords)

            $.each(pegawaiRecords, function(key, item) {
                if ($(\"#laporan-pgndaftar option[value=\" + key +\"]\").length < 1) {
                    var o = new Option(item, key);
                    $(o).html(item);
                    $('#laporan-pgndaftar').append(o);
                }
            });

            $('#laporan-pgndaftar').val(arrDataInit).trigger('change');
        }, 500);
    });
    ", View::POS_END);
 }
else {
    $this->registerJs("
    $(document).ready(function () {
        $('#laporan-pgndaftar').multi({
            search_placeholder: 'Cari...',

        });

        
    });
    ", View::POS_END);    
}


