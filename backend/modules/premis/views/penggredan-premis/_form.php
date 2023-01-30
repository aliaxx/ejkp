<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use kartik\widgets\DateTimePicker;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use common\utilities\DateTimeHelper;
use codetitan\widgets\LookupInput;

use common\models\Pengguna;
use backend\modules\peniaga\utilities\OptionHandler;


/* @var $this yii\web\View */
/* @var $model backend\modules\peniaga\models\penggredanpremis */
/* @var $form yii\widgets\ActiveForm */

//display data daripada tbparamdetail dimana kodjenis=TUJUAN LAWATAN.
$data['ID_TUJUAN'] = ['',''];
$source = Yii::$app->db->createCommand("SELECT * FROM TBPARAMETER_DETAIL WHERE KODJENIS = 22")->queryAll();
$data['ID_TUJUAN'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

//display data daripada tbparamdetail dimana kodjenis=TUJUAN LAWATAN.
$data['IDDUN'] = ['',''];
$source = Yii::$app->db->createCommand("SELECT * FROM TBDUN")->queryAll();

$data['IDDUN'] = ArrayHelper::map($source, 'ID', 'PRGNDUN');

$data['IDLOKASI'] = ['',''];
// $source = Yii::$app->db->createCommand("SELECT * FROM C##ESEWA.V_LOCATION_LIST")->queryAll();
$source = Yii::$app->db->createCommand("SELECT * FROM C##ESEWA.SW_LOCATIONS")->queryAll();

$data['IDLOKASI'] = ArrayHelper::map($source, 'LOCATION_ID', 'LOCATION_NAME');

$data['PRGNLOKASI_AM'] = [''];

$data['PRGNLOKASI_AM'] = ['' => ''];
$url['LOKASI_AM'] = Url::to(['/option/penyelenggaraan/lokasi-am']);

$model->IDMODULE='PPM';

$sources= Yii::$app->db->createCommand("SELECT PRGN FROM TBMODULE WHERE ID ='$model->IDMODULE' ")->queryOne();
// $source = Yii::$app->db->createCommand("SELECT * FROM TBLOKASI")->queryAll();
$model->prgnidmodule = $sources['PRGN'];  //'Kawalan Perniagaan';

//lookup for no.lesen
$url['NOLESEN'] = Url::to(['/lookup/vektor/lesen', 'target' => 'lawatanmain-nolesen']); //target -> nama object, akan panggil kat js script.

$initVal['NOLESEN'] = null;

$data['KETUAPASUKAN'] = [];
$url['KETUAPASUKAN'] = Url::to(['/option/penyelenggaraan/pengguna']);

$disable['IDLOKASI'] = false;

//get current date
date_default_timezone_set("Asia/Kuala_Lumpur");

// Then call the date functions
$date = date('d-m-Y, H:i');

if($model->NOSIRI){
    $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, false, true);
    $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, false, true); 
}else{
    $model->TRKHMULA=$date;
    $model->TRKHTAMAT=$date;
}

if (!$model->isNewRecord) {
    $disable['IDLOKASI'] = true;

    // set empty array for ahli pasukan attribute
    $model->ahlipasukan = [];
    // set ketua pasukan
    if ($model->ketuapasukan0) {
        $model->KETUAPASUKAN = $model->ketuapasukan0->IDPENGGUNA;
    }

    // loop pasukanAhlis array and add it inside ahlipasukan attribute
    foreach ($model->pasukanAhlis as $ahli) {
        if ($ahli->JENISPENGGUNA == 2) {
            if (!empty(Pengguna::findOne(['ID' => $ahli->IDPENGGUNA, 'STATUS' => 1]))) {
                $model->ahlipasukan[] = $ahli->IDPENGGUNA;
            }
        }
    }

    // set ketuapasukan data display on dropdown field
    if ($model->ketuapasukan0) {
        $data['KETUAPASUKAN'] = [$model->KETUAPASUKAN => $model->ketuapasukan0->pengguna0->NAMA];
    }

    if ($model->IDZON_AM) {
        $data['PRGNLOKASI_AM'] = [$model->PRGNLOKASI_AM => $model->PRGNLOKASI_AM];
    }

    //GET RECORD FROM TBLAWATAN_PEMILIK
    if($model->pemilik0){

        // var_dump($initVal['PEMILIKLESEN']->NOLESEN);
        // exit();
        $initVal['PEMILIKLESEN'] = $model->pemilik0;
        $model->NOLESEN = $initVal['PEMILIKLESEN']->NOLESEN;
        $model->NOLESEN1 = $initVal['PEMILIKLESEN']->NOLESEN;
        $model->NOSSM = $initVal['PEMILIKLESEN']->NOSSM;
        $model->KETERANGANKATEGORI = $initVal['PEMILIKLESEN']->KETERANGAN_KATEGORI;
        $model->JENISJUALAN = $initVal['PEMILIKLESEN']->JENIS_JUALAN;
        $model->NAMAPEMOHON = $initVal['PEMILIKLESEN']->NAMAPEMOHON;
        $model->NOKPPEMOHON = $initVal['PEMILIKLESEN']->NOKPPEMOHON;
        $model->NAMASYARIKAT = $initVal['PEMILIKLESEN']->NAMASYARIKAT;
        $model->NAMAPREMIS = $initVal['PEMILIKLESEN']->NAMAPREMIS;
        $model->ALAMAT1 = $initVal['PEMILIKLESEN']->ALAMAT1;
        $model->ALAMAT2 = $initVal['PEMILIKLESEN']->ALAMAT2;
        $model->ALAMAT3 = $initVal['PEMILIKLESEN']->ALAMAT3;
        $model->POSKOD = $initVal['PEMILIKLESEN']->POSKOD;
        $model->JENIS_PREMIS = $initVal['PEMILIKLESEN']->JENIS_PREMIS;
        $model->NOTEL = $initVal['PEMILIKLESEN']->NOTEL;
    }  
}

if ($model->isNewRecord) {
    $model->setNoSiri('PPM'); //set and display nosiri -NOR27092022 
}

?>


<?php $form = ActiveForm::begin([
        'id' => 'lawatanmain-form', //ID TU REFER NAMA MODEL-TENGOK DEKAT F12 NAK CONFIRM (alia-080922)
    ]); ?>

    <?= \codetitan\widgets\ActionBar::widget([
        'permissions' => ['save2close' => Yii::$app->access->can('penggredanpremis-write')],
    ]) ?>
              
<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Maklumat Lawatan Premis') ?>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">  
                    <!-- lAWATAN PREMIS Forms -->
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'TRKHMULA')->textInput()->widget(DateTimePicker::className(), [
                                'name' => 'TRKHMULA',
                                'readonly' => true,
                                'options' => ['class' => 'custom-datetime-field'],
                                'pluginOptions' => [
                                    'minuzonamep' => 1,
                                    'todayHighlight' => true,
                                    'format' => 'dd-mm-yyyy, hh:ii',
                                    'autoclose' => true,
                                    'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                ]
                            ]);?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'TRKHTAMAT')->textInput()->widget(DateTimePicker::className(), [
                                'name' => 'TRKHTAMAT',
                                'readonly' => true,
                                'options' => ['class' => 'custom-datetime-field'],
                                'pluginOptions' => [
                                    'minuzonamep' => 1,
                                    'todayHighlight' => true,
                                    'format' => 'dd-mm-yyyy, hh:ii',
                                    //'format' => 'Y-m-d H:i:s',
                                    'autoclose' => true,
                                    'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                ]
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOSIRI')->textInput(['disabled' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'prgnidmodule')->textInput(['disabled' => true]) ?>
                            <?= $form->field($model, 'IDMODULE')->textInput(['type' => 'hidden'])->label(false) ?>
                            <?= $form->field($model, 'IDSUBUNIT')->textInput(['type' => 'hidden'])->label(false) ?>
                            


                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'JENISPREMIS')->dropDownList(OptionHandler::render('jenis-premis'), ['prompt' => '']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'ID_TUJUAN')->widget(Select2::className(), [
                                'data' => $data['ID_TUJUAN'],
                                'options' => [
                                    'placeholder' => '',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <?= $form->field($model, 'PRGNLOKASI_AM')->widget(Select2::classname(), [
                                'data' => $data['PRGNLOKASI_AM'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'ajax' => [
                                        'url' => $url['LOKASI_AM'],
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                        'processResults' => new JsExpression('function(data, params) {
                                            params.page = params.page || 1;
                                            return {
                                                results: data.results,
                                                pagination: { more: (params.page * 20) < data.total }
                                            };
                                        }'),
                                    ],
                                ],
                                'options' => [
                                    'placeholder' => '',
                                ],
                            ]); ?>
                        </div>


                        <?php if ($model->IDMODULE =="SMM"): ?>
                            <div class="col-md-6">
                                <?= $form->field($model, 'IDLOKASI')->widget(Select2::className(), [
                                    'data' => $data['IDLOKASI'],
                                    'options' => [
                                        'placeholder' => '',
                                        'disabled' => $disable['IDLOKASI'],
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ]
                                ]) ?>
                                <?= $form->field($model, 'IDLOKASI1')->textInput(['type' => 'hidden'])->label(false) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'IDDUN')->widget(Select2::className(), [
                                'data' => $data['IDDUN'],
                                'options' => [
                                    'placeholder' => '',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOADUAN') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'LATITUD')->textInput(['id' => 'lat'])?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'LONGITUD')->textInput(['id' => 'long']) ?>
                        </div>
                    </div>
                    <div class="row">
                    <h5 style="margin-top:0px;">&nbsp;&nbsp;&nbsp;<u>Pengendali Makanan</u></h5>
                        <div class="col-md-4">
                        <?= $form->field($model, 'PP_BILPENGENDALI')->textInput(['type' => 'number', 'min' => 1, 'readonly' => false])->label('Bil. Pengendali')?>
                        </div>
                        <div class="col-md-4">
                        <?= $form->field($model, 'PP_SUNTIKAN_ANTITIFOID')->textInput(['type' => 'number', 'min' => 1, 'readonly' => false])->label('Suntikan Pelalian Anti-Tifoid') ?>
                        </div>
                        <div class="col-md-4">
                        <?= $form->field($model, 'PP_KURSUS_PENGENDALI')->textInput(['type' => 'number', 'min' => 1, 'readonly' => false])->label('Kursus Pengendali Makanan') ?>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'CATATAN')->textArea(['style' => 'width: 1325px; height: 75px'])?>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php $checkboxTemplate = '<label class="col-sm-12 control-label pull-right"><b>Penggredan Tandas?</b></label>'; ?>
                                <?= $form->field($model, 'ISTANDAS')->checkbox(['style' => 'width: 200%; margin-right: 200%; margin-top: 10px;',
                                    'label' => $checkboxTemplate,
                                    'class' => 'checkbox-inline',
                                            ]) //Jika OKK bersetuju membayar amaun kompaun yang ditawarkan
                                ?>
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->render('@backend/modules/integrasi/views/lesen/_search', [
    'form' => $form,
    'model' => $model,
]) ?>
<!-- Pegawai Pemeriksa forms-->
<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Maklumat Pegawai') ?>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'KETUAPASUKAN')->widget(Select2::className(), [
                        'data' => $data['KETUAPASUKAN'],
                        'options' => [
                            'placeholder' => '',
                            // 'disabled' => $model->isNewRecord ? false : true,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'placeholder' => '',
                            'ajax' => [
                                'url' => $url['KETUAPASUKAN'],
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                'processResults' => new JsExpression('function(data, params) {
                                    params.page = params.page || 1;
                                    return {
                                        results: data.results,
                                        pagination: { more: (params.page * 20) < data.total }
                                    };
                                }'),
                            ],
                        ],
                    ]) ?>

                    <?= $form->field($model, 'ahlipasukan')->dropDownList([], [
                            'multiple' => true,
                        ]) ?>

                    <?= $form->field($model, '_inputahlipasukan')->hiddenInput()->label(false) ?>
                </div>
            </div>
        </div>
    </div>
</div>

                
<?php if ($model->IDMODULE=="KPN"): ?>
<!-- Penerima forms--> 
   
<?= $form->field($model, 'NAMAPENERIMA')->hiddenInput()->label(false) ?>          
<?php else: ?>  
    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Maklumat Penerima') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                    <?= $form->field($model, 'NAMAPENERIMA')->textInput()?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Submit buttons -->
<div class="row">
    <div class="col-md-12">
        <span class="pull-right">              
            <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['index'], [
                'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]',
                'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
            ]) ?>

            <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1,
                'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
            ]) ?>
        </span>
    </div>
</div>
    <?php ActiveForm::end(); ?>       
</div>

<?php
$this->registerCssFile(Yii::getAlias('@web/css/multi.css'));
$this->registerJsFile(Yii::getAlias('@web/js/multi_faeezrev_v2.js'), ['depends' => 'yii\web\YiiAsset']);

// // for edit form
// if (!$model->isNewRecord) { 
//     $this->registerJs("
//     $(document).ready(function () {
        
//         populateLesen('" . $model->pemilik0->NOLESEN . "');
//         //$('#lawatanmain-NOLESEN').val('" . $model->pemilik0->NOLESEN . "');
//     });
//     ", View::POS_END);
// }



//untuk ahli pasukan
$this->registerJs("
var _tmpMultiSelect;

// hold jenis ahli value
var jenisahli = null;

// hold selected ahlipasukan option elements
var selectedAhli = [];

// hold selected ahlipasukan value
var ahliValues = [];

getPengguna();

function getPengguna()
{ 
    $.get('" . Url::to(['/option/penyelenggaraan/list']) . "', function (data) {

        if (data.success) {
            selectedAhli = $('#lawatanmain-ahlipasukan option:selected').toArray();

            ahliValues = $('#lawatanmain-ahlipasukan option:selected').map(function() {
                return this.value;
            }).get();

            $('#lawatanmain-ahlipasukan')[0].options.length = 0;

            $.each(selectedAhli, function(key, item) {
                $('#lawatanmain-ahlipasukan').append(item);
            });

            $.each(data.results, function(key, item) {

                if ( (jQuery.inArray(key, ahliValues) == -1) ) {
                    var o = new Option(item, key);

                    $(o).html(item);
                    $('#lawatanmain-ahlipasukan').append(o);
                }
            });
            $('#lawatanmain-ahlipasukan').trigger('change');
        }
    });
}

$(document).ready(function () {
    $('#lawatanmain-form').submit(function(event){
        $('#lawatanmain-_inputahlipasukan').val(_tmpMultiSelect);
        return true;
    });
});
", View::POS_END);


if (!$model->isNewRecord) {
    $this->registerJs("
    // hold value to see if ahlipasukan attribute has value
    var ahliRecords = [];

    $(document).ready(function () {
        // get ahliRecords list using ajax
        // $.get('" . Url::to(['/inventori/pasukan/pengurusan/get-ahlis']) . "', function (data) {
        $.get('" . Url::to(['/peniaga/kawalan-perniagaan/get-ahlis'])  . "', { NOSIRI: '". $model->NOSIRI ."' }, function (data) {
            if (data) {
                ahliRecords = data;
            }
        });

        // convert ahlipasukan into string
        var ahlipasukan = '" . implode(',', $model->ahlipasukan) . "';

        // convert string ahlipasukan into array for js
        var tmpPasukanData = ahlipasukan.split(',');

        // predefinded variable
        var arrData = [];
        var arrDataInit = [];

        // loop ahlipasukan array
        $.each(tmpPasukanData, function (i, val) {
            // array for selected ahlipasukan dropdown list
            arrData[i] = val;
            
            // array for hidden selected ahlipasukan dropdown list
            arrDataInit[i] = parseInt(val);
        });

        _tmpMultiSelect = arrData;

        // setup ahlipasukan dropdown field html and configs
        $('#lawatanmain-ahlipasukan').multi({
            search_placeholder: 'Cari...',
            compare: 'lawatanmain-ketuapasukan',
            multiSelected: arrData,
            compareErrorText: '" . Yii::t('app', 'Ketua pasukan tidak boleh berada di dalam ahli pasukan') . "'
        });

        window.setTimeout(function () {            
            $.each(ahliRecords, function(key, item) {
                if ($(\"#lawatanmain-ahlipasukan option[value=\" + key +\"]\").length < 1) {
                    var o = new Option(item, key);
                    $(o).html(item);
                    $('#lawatanmain-ahlipasukan').append(o);
                }
            });
            $('#lawatanmain-ahlipasukan').val(arrDataInit).trigger('change');
        }, 500);
        
    });
    ", View::POS_END);
} else {
    $this->registerJs("
    $(document).ready(function () {
        $('#lawatanmain-ahlipasukan').multi({
            search_placeholder: 'Cari...',
            compare: 'lawatanmain-ketuapasukan',
            compareErrorText: '" . Yii::t('app', 'Ketua pasukan tidak boleh berada di dalam ahli pasukan') . "'
        });
    });
    ", View::POS_END);
}


