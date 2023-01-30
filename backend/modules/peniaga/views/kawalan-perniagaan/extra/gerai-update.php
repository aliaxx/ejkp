<?php

use backend\modules\peniaga\models\KawalanPerniagaan;
use backend\modules\peniaga\models\KawalanPerniagaanSearch;
use backend\modules\peniaga\models\Transgerai;
use backend\modules\peniaga\models\TransgeraiSearch;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\utilities\DateTimeHelper;

use backend\modules\peniaga\utilities\OptionHandler;
use backend\modules\peniaga\models\LawatanPemilik;

use kartik\widgets\DepDrop;

/**
 * @var View $this
 * @var Datakes $model
 * @var DatakesApp $modelGerai
 * @var ActiveDataProvider $dataProvider
 */

$this->title = 'Gerai/Petak';
$this->params['breadcrumbs'] = [
    'Peniaga Kecil & Penjaja',
    ['label' => 'Kawalan Perniagaan', 'url' => ['index']],
    $model->NOSIRI,
    ['label' => $this->title, 'url' => ['kawalan-perniagaan/gerai', 'nosiri' => $modelGerai->NOSIRI]],
    'Kemaskini Maklumat'
];

$flag['newRecord'] = false;

$data['NOPETAK'] = ['' => ''];
$url['list-petak'] = Url::to(['/option/penyelenggaraan/list-petak', 'locationid'=>$model->IDLOKASI]);

$modelGerai->IDMODULE='KPN';

$modelGerai->TRKHLAWATAN_GERAI=date('d/m/Y', strtotime($model->TRKHMULA));

if (!$modelGerai->isNewRecord) {

    if($modelGerai->idPemilik0){

        if ($modelGerai->NOPETAK) {
            $data['NOPETAK'] = [$modelGerai->NOPETAK = $modelGerai->idPemilik0->NOPETAK];
        }
        // $modelGerai->NOSIRI = $modelGerai->idPemilik0->NOSIRI;
        $modelGerai->IDMODULE= $modelGerai->idPemilik0->IDMODULE;
        $modelGerai->NOSEWA= $modelGerai->idPemilik0->NOSEWA;
        $modelGerai->NOLESEN= $modelGerai->idPemilik0->NOLESEN;
        $modelGerai->NOPETAK1= $modelGerai->idPemilik0->NOPETAK;
        $modelGerai->JENISSEWA= $modelGerai->idPemilik0->JENISSEWA;
        $modelGerai->JENIS_JUALAN = $modelGerai->idPemilik0->JENIS_JUALAN;
        $modelGerai->NAMAPEMOHON = $modelGerai->idPemilik0->NAMAPEMOHON;
        $modelGerai->NOKPPEMOHON = $modelGerai->idPemilik0->NOKPPEMOHON;
        $modelGerai->NOTEL= $modelGerai->idPemilik0->NOTEL;
        $modelGerai->ALAMAT1 = $modelGerai->idPemilik0->ALAMAT1;
        $modelGerai->ALAMAT2 = $modelGerai->idPemilik0->ALAMAT2;
        $modelGerai->ALAMAT3 = $modelGerai->idPemilik0->ALAMAT3;
        $modelGerai->POSKOD = $modelGerai->idPemilik0->POSKOD;
    }

}
?>

<div style="margin-top:61px;"></div>
<div>
    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>
</div>
<br><br>

<?php $output = GridView::widget([
        'id' => 'primary-grid',
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-striped table-condensed table-hover table-responsive'],
        'layout' => '{items}',
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['class' => 'text-center', 'style' => 'width:25px'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            'ID',
            [
                'attribute' => 'NOPETAK',
                'value' => 'idPemilik0.NOPETAK',
            ],
            [
                'label' => 'No Sewa',
                'attribute' => 'NOSEWA',
                'value' => 'idPemilik0.NOSEWA',
            ],
            [
                'label' => 'Nama Pemohon',
                'attribute' => 'NAMAPEMOHON',
                'value' => 'idPemilik0.NAMAPEMOHON',
            ],
            [
                'format' => 'date',
                'attribute' => 'TRKHLAWATAN_GERAI',
            ],
            'NORUJUKAN',
            [
                'attribute' => 'STATUSPEMANTAUAN',
                'value' => function($model) {
                    return OptionHandler::resolve('status-pemantauan', $model->STATUSPEMANTAUAN);
                }
            ],
            [
                'label' => 'Pengguna Akhir',
                'attribute' => 'PGNAKHIR',
                'value' => function ($model) {
                    return ($pengguna = \common\models\Pengguna::findOne(['ID' => $model->PGNAKHIR]))? $pengguna->NAMA : null;
                },
            ],
            [
                'label' => 'Tarikh Kemaskini',
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Tindakan',
                'template' => "{view} {update} {delete}",
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['gerai-view', 'nosiri' => $model->NOSIRI, 'ID' => $model->ID]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['gerai-update', 'nosiri' => $model->NOSIRI, 'ID' => $model->ID]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['get-gerai-delete'], [
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $model->NOSIRI, 'ID' => $model->ID,
                            ],
                        ]);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('kawalan-perniagaan-read'),
                    'update' => \Yii::$app->access->can('kawalan-perniagaan-write'),
                    'delete' => \Yii::$app->access->can('kawalan-perniagaan-write'),
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>
    

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:20px'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>


<br>

<h4>Kemaskini Rekod Lawatan Gerai dan Pasar &nbsp;&nbsp;&nbsp;&nbsp;
<?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar Rekod Baru', ['kawalan-perniagaan/gerai', 'nosiri' => $modelGerai->NOSIRI], [
            'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
            'aria-label' => 'Daftar', 'data-pjax' => 0,
]) ?>

</h4>


<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Maklumat Gerai/Petak') ?>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">  
                    <div class="row">
                        <div class="row">
                            <div class="col-md-6">
                            <!-- <?= $form->field($modelGerai, 'NOPETAK')->textInput(['maxlength' => true]) ?> -->
                            <?= $form->field($modelGerai, 'NOPETAK')->widget(Select2::classname(), [
                                'data' => $data['NOPETAK'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'ajax' => [
                                        'url' => $url['list-petak'],
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
                            ])->label('Pilih No Gerai')?>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <?= $form->field($modelGerai, 'NOPETAK1')->textInput(['maxlength' => true])->label('No Gerai')?>  
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'TRKHLAWATAN_GERAI')->widget(DatePicker::className(), [
                                    'options' => ['placeholder' => ''],
                                    'pluginOptions' => [
                                        'format' => 'dd/mm/yyyy',
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                    ]
                                ])  ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- <?= $form->field($modelGerai, 'NOSEWA') ->textInput(['maxlength' => true])->label('No Lesen')?>   -->
                                <?= $form->field($modelGerai, 'NOSEWA')->textInput([
                                    'id' => 'transgerai-nosewa',
                                    'autocomplete' => 'off',
                                    'readonly' => false,
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'ajax' => [
                                            'url' =>  Url::to(['/peniaga/kawalanperniagaan/get-maklumat-sewa']),
                                            'dataType' => 'json',
                                            'data' => new JsExpression('function(params) { return {search:params.term, page:params.page, ID: $("#transgerai-nopetak").val()}; }'),
                                            'processResults' => new JsExpression('function(data, params) {
                                                params.page = params.page || 1;
                                                return {
                                                    results: data.results,
                                                    pagination: { more: (params.page * 20) < data.total }
                                                };
                                            }'),
                                        ],
                                    ],
                                ])->label('No Sewa')?>  
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'NOLESEN') ->textInput(['maxlength' => true])->label('No Lesen')?>
                                <?= $form->field($modelGerai, 'IDMODULE')->textInput(['type' => 'hidden'])->label(false) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'NAMAPEMOHON') ->textInput(['maxlength' => true])->label('Nama Pemilik/ Pemohon')?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'NOKPPEMOHON') ->textInput(['maxlength' => true])->label('No KP/ Pasport')?>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'JENISSEWA') ->textInput(['maxlength' => true])->label('Jenis Sewa')?>
                            </div>
                            <div class="col-md-6">
                            <?= $form->field($modelGerai, 'JENIS_JUALAN') ->textInput(['readonly' => true])->label('Jenis Jualan')?> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'ALAMAT1') ->textInput(['maxlength' => true])->label('Alamat Premis 1')?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'ALAMAT2') ->textInput(['maxlength' => true])->label('Alamat Premis 2')?>
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'ALAMAT3') ->textInput(['maxlength' => true])->label('Alamat Premis 3')?>
                            </div>
                            <div class="col-md-6">
                            <?= $form->field($modelGerai, 'POSKOD') ->textInput(['maxlength' => true])?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'NOTEL') ->textInput(['maxlength' => true])->label('No Tel')?> 
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'STATUSGERAI')->dropDownList(OptionHandler::render('status-gerai'), ['prompt' => '']) ?>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'PRGKP_GREASE')->dropDownList(OptionHandler::render('perangkap-grease'), ['prompt' => '']) ?> 

                            </div>
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'TINDAKANPENGUATKUASA')->dropDownList(OptionHandler::render('tindakan-penguatkuasa'), ['prompt' => '']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'NORUJUKAN')->textInput(['maxLength' => true]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'STATUSPEMANTAUAN')->dropDownList(OptionHandler::render('status-pemantauan'), ['prompt' => '']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($modelGerai, 'CATATAN')->textInput(['maxlength' => true])->label('Catatan') ?>
                            </div>
                        </div>
                            
                    </div>
                    </div>
            </div>
            
        </div>
    </div>
</div>
    <?php if($modelGerai): ?> 
    <div class="row">
        <div class="col-md-offset-10 col-md-2">
            <!-- <div style="margin-left:150px"> -->
            <?= Html::submitButton('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', [
                        'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
                        'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
            ]) ?>
            <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1,
                'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
            ]) ?>
            <!-- </div> -->
        </div>
    </div>
    <?php endif ?>
    <?php ActiveForm::end() ?>

    <hr />

   
</div>
<?php
$this->registerJs("
/// Autofill form when choose value from dropdown list lot no.
$(document).ready(function(){
    $('#transgerai-nopetak').change(function() {        
        // alert('hai alia');  
        var lotno = $('#transgerai-nopetak option:selected').val();
        // alert(lotno);
        // alert($('#transgerai-nossm'));
        $.get('".Url::to(['/peniaga/kawalan-perniagaan/get-maklumat-sewa'])."', {lotno: lotno}, function (data){
            // console.log(data.results);
            // alert(data.results.ACCOUNT_NUMBER); 
            // alert(lotno);
            // // alert($('#transgerai-nosewa').val(data.results.LOT_NO));
            $('#transgerai-nopetak1').val(data.results.LOT_NO);
            $('#transgerai-nosewa').val(data.results.ACCOUNT_NUMBER);
            $('#transgerai-nolesen').val(data.results.LICENSE_NUMBER);
            $('#transgerai-namapemohon').val(data.results.NAME);
            // $('#transgerai-idlokasi').val(data.results.LOCATION_ID);
            $('#transgerai-jenissewa').val(data.results.RENT_CATEGORY);
            $('#transgerai-jenis_jualan').val(data.results.SALES_TYPE);
            $('#transgerai-alamat1').val(data.results.ASSET_ADDRESS_1);
            $('#transgerai-alamat2').val(data.results.ASSET_ADDRESS_2);
            $('#transgerai-alamat3').val(data.results.ASSET_ADDRESS_3);
            $('#transgerai-poskod').val(data.results.ASSET_ADDRESS_POSTCODE);
        });
    });
});
", \yii\web\View::POS_END);
