<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
// use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;

use kartik\widgets\DateTimePicker;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use common\utilities\DateTimeHelper;
use codetitan\widgets\LookupInput;

use backend\modules\vektor\utilities\OptionHandler;
use backend\modules\penyelenggaraan\models\ParamDetail;
use backend\modules\vektor\models\BekasLvc;

// var_dump();
// exit();

$this->title = 'Kompaun/Notis';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
['label' => 'Pemeriksaan Tempat Pembiakan Aedes(PTP)', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/vektor/ptp/penguatkuasaan', 'nosiri' => $model->NOSIRI]],
];

$url['premis'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '8']);
$initVal['premis'] = null;
if ($penguatkuasaan->ID_JENISPREMIS) {
    $object = ParamDetail::findOne(['KODDETAIL' => $penguatkuasaan->ID_JENISPREMIS, 'KODJENIS' => '8']);
    $initVal['premis'] = $object->PRGN;
}

$url['liputan'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '25']);
$initVal['liputan'] = null;
if ($penguatkuasaan->LIPUTAN) {
    $object = ParamDetail::findOne(['KODDETAIL' => $penguatkuasaan->LIPUTAN, 'KODJENIS' => '25']);
    $initVal['liputan'] = $object->PRGN;
}

$url['jenis-pembiakan'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '27']);
$initVal['jenis-pembiakan'] = null;
if ($penguatkuasaan->ID_JENISPEMBIAKAN) {
    $object = ParamDetail::findOne(['KODDETAIL' => $penguatkuasaan->ID_JENISPEMBIAKAN, 'KODJENIS' => '27']);
    $initVal['jenis-pembiakan'] = $object->PRGN;
}

$url['tindakan'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '28']);
$initVal['tindakan'] = null;
if ($penguatkuasaan->ID_TINDAKAN) {
    $object = ParamDetail::findOne(['KODDETAIL' => $penguatkuasaan->ID_TINDAKAN, 'KODJENIS' => '28']);
    $initVal['tindakan'] = $object->PRGN;
}

$url['sebab'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '29']);
$initVal['sebab'] = null;
if ($penguatkuasaan->ID_SEBABNOTIS) {
    $object = ParamDetail::findOne(['KODDETAIL' => $penguatkuasaan->ID_SEBABNOTIS, 'KODJENIS' => '29']);
    $initVal['sebab'] = $object->PRGN;
}

//get current date
date_default_timezone_set("Asia/Kuala_Lumpur");

// Then call the date functions
$date = date('d-m-Y, H:i');



// if($penguatkuasaan->NOSIRI){
//     $penguatkuasaan->TRKHSALAH = DateTimeHelper::convert($penguatkuasaan->TRKHSALAH, false, true);
// }else{
//     $penguatkuasaan->TRKHMULA=$date;
// }

if (!$penguatkuasaan->isNewRecord) {
    $penguatkuasaan->TRKHSALAH = DateTimeHelper::convert($penguatkuasaan->TRKHSALAH, false, true);
}else{
    $penguatkuasaan->TRKHSALAH = $date;
}

?>

<style>

.danger {
  background-color: #ffdddd;
  border-left: 6px solid #f44336;
  /* padding: 10px; */
  /* margin-left: 15px; */
  /* float: right; */
  line-height: 40px;
  /* cursor: pointer;
  transition: 0.3s; */
  color: black;
  /* font-size: 14px; */
}

.alert{
    padding:10px;
}

/* img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 40%;
} */

</style>

<div style="margin-top:61px;"></div>
<?= $this->render('_tab', ['model' => $model]) ?>
<br>

<div>

<?php $form = ActiveForm::begin(['method' => 'post', 'options'=> ['target' => '_blank']]); ?>
    <?= \codetitan\widgets\ActionBar::widget([
        'target' => 'primary-grid',
        'permissions' => ['new' => Yii::$app->access->can('PTP-write')],
    ]) ?> 

    <!-- <div class="action-buttons"> -->
        <!-- <?= $this->render('_printkompaun') ?> -->
    <!-- </div> -->
<?php ActiveForm::end(); ?>

<div>
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
            // 'NOSIRI',
            'NOSAMPEL',
            [
                'attribute' => 'JENIS',
                'value' => function($penguatkuasaan) {
                    return OptionHandler::resolve('jenis-tindakan', $penguatkuasaan->JENIS);
                }
            ],
            // 'NOLOT',
            // 'BANGUNAN',
            // 'TAMAN',
            'NAMAPESALAH',
            [
                'attribute' => 'ID_JENISPREMIS',
                'value' => 'premis.PRGN',
            ],
            [
                'attribute' => 'LIPUTAN',
                'value' => 'liputan.PRGN',
            ],
            [
                'attribute' => 'ID_JENISPEMBIAKAN',
                'value' => 'jenisPembiakan.PRGN',
            ],
            // 'ID_TINDAKAN',
            // 'ID_SEBABNOTIS',
            // 'BILBEKASMUSNAH',
            // 'LATITUDE', 
            // 'LONGITUDE',
            // 'TRKHSALAH',
            [
                // 'attribute' => 'TRKHSALAH',
                // 'value' => date('d-m-Y H:i', strtotime($penguatkuasaan->TRKHSALAH)),
                'attribute' => 'TRKHSALAH',
                'value' => function ($penguatkuasaan) {
                    return date('d-m-Y H:i', strtotime($penguatkuasaan->TRKHSALAH));
                },
            ],
            [  //view jenis bekas
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Jenis Bekas',
                'template' => "{jenisbekas}",
                'headerOptions' => ['style' => 'width:30px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'jenisbekas' => function ($url, $penguatkuasaan) {
                        $options = [
                            'title' => 'Senarai Capaian',
                            'aria-label' => 'Akses',
                            'data-pjax' => '0',
                        ];

                        return Html::a('<span class="fa fa-th-list" style="color:green"></span>', ['jenis-bekas', 'nosiri' => $penguatkuasaan->NOSIRI, 
                        'nosampel' => $penguatkuasaan->NOSAMPEL, 'id' => $penguatkuasaan->ID], $options);
                    },
                ],
                'visibleButtons' => [
                    'jenisbekas' => function ($penguatkuasaan) {
                        return \Yii::$app->access->can('PTP-write');
                    },
                ],
            ],
            // 'PGNDAFTAR',
            // 'TRKHDAFTAR',
            [
                'attribute' => 'PGNAKHIR',
                'value' => 'createdByUser.NAMA', 
            ],
            'TRKHAKHIR:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => "Tindakan",
                'template' => "{cetak} {view} {update} {delete}",
                'headerOptions' => ['style' => 'width:70px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'cetak' => function ($url, $penguatkuasaan) {
                        return Html::a('<span class="fa fa-print" style="color:green"></span>', ['print-kompaun', 'nosiri' => $penguatkuasaan->NOSIRI, 'ID' => $penguatkuasaan->ID], ['target' => '_blank']);
                    },
                    'view' => function ($url, $penguatkuasaan) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open" style="color:green"></i>', ['penguatkuasaan-view', 'nosiri' => $penguatkuasaan->NOSIRI, 'ID' => $penguatkuasaan->ID]);
                    },
                    'update' => function ($url, $penguatkuasaan) {
                        return Html::a('<i class="glyphicon glyphicon-pencil" style="color:green"></i>', ['penguatkuasaan', 'nosiri' => $penguatkuasaan->NOSIRI, 'idpenguatkuasaan' => $penguatkuasaan->ID]); //'nosiri', 'idsampel' are parameter in controller action -NOR06092022
                    },
                    'delete' => function ($url, $penguatkuasaan) {
                        return Html::a('<i class="glyphicon glyphicon-trash" style="color:green"></i>', ['penguatkuasaan-delete'], [
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan dipadamkan. Maklumat penguatkuasaan akan dikemaskini mengikut tarikh tindakan terkini. Teruskan?', //display onclick -NOR120922
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $penguatkuasaan->NOSIRI, 'idpenguatkuasaan' => $penguatkuasaan->ID,
                            ],
                        ]);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('PTP-read'),
                    'update' => \Yii::$app->access->can('PTP-write'),
                    'delete' => \Yii::$app->access->can('PTP-write'),
                ],
            ],
        ],
    ]); 
    ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>

    <div>
        <?php if($penguatkuasaan->isNewRecord): ?>
            <h4 style="margin-top:20px;">Daftar Rekod Kompaun/Notis</h4>
        <?php else : ?>
            <h4 style="margin-top:20px;">Kemaskini Rekod Kompaun/Notis
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar Rekod Baru', ['/vektor/ptp/penguatkuasaan', 'nosiri' => $penguatkuasaan->NOSIRI], [
                        'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
                        'aria-label' => 'Daftar', 'data-pjax' => 0,
            ]) ?>
            </h4>    
        <?php endif; ?>

        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:20px'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
                'labelOptions' => ['class' => 'col-lg-3 control-label'],
            ],
        ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($penguatkuasaan, 'JENIS')->radioList(OptionHandler::render('jenis-tindakan'), ['selector'=>'radio', 'inline'=>true]); ?>
        </div>
    </div>
    <div class="row">
        <!-- left side -->
        <div class="col-md-6">
            <?= $form->field($penguatkuasaan, 'NOLOT')->textInput(['maxlength' => true]) ?>
     
            <?= $form->field($penguatkuasaan, 'TAMAN')->textInput(['maxlength' => true]) ?> 

            <?= $form->field($penguatkuasaan, 'ID_JENISPREMIS')->widget(Select2::className(), [
                'initValueText' => $initVal['premis'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => '',
                    'ajax' => [
                        'url' => $url['premis'],
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
                // 'options' => ['disabled' => !$penguatkuasaan->isNewRecord],
            ]) ?>

            <?= $form->field($penguatkuasaan, 'LIPUTAN')->widget(Select2::className(), [
            'initValueText' => $initVal['liputan'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['liputan'],
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
            
            <?= $form->field($penguatkuasaan, 'NOSAMPEL')->textInput(['maxlength' => true]) ?>

            <?= $form->field($penguatkuasaan, 'ID_SEBABNOTIS')->widget(Select2::className(), [
            'initValueText' => $initVal['sebab'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['sebab'],
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

            <?= $form->field($penguatkuasaan, 'LATITUDE')->textInput(['id' => 'lat'])?>

        </div>

        <!-- right side -->
        <div class="col-md-6">
            <?= $form->field($penguatkuasaan, 'BANGUNAN')->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($penguatkuasaan, 'NAMAPESALAH')->textInput(['maxlength' => true]) ?>
                        
            <?= $form->field($penguatkuasaan, 'TRKHSALAH')->textInput()->widget(DateTimePicker::className(), [
                'name' => 'TRKHSALAH',
                'readonly' => true,
                'options' => ['class' => 'custom-datetime-field'],
                'pluginOptions' => [
                    'minuzonamep' => 1,
                    'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy, hh:ii',
                    'autoclose' => true,
                    'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                ]]);
            ?>

            <?= $form->field($penguatkuasaan, 'ID_TINDAKAN')->widget(Select2::className(), [
                'initValueText' => $initVal['tindakan'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => '',
                    'ajax' => [
                        'url' => $url['tindakan'],
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
            
            <?= $form->field($penguatkuasaan, 'ID_JENISPEMBIAKAN')->widget(Select2::className(), [
                'initValueText' => $initVal['jenis-pembiakan'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => '',
                    'ajax' => [
                        'url' => $url['jenis-pembiakan'],
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

            <?= $form->field($penguatkuasaan, 'BILBEKASMUSNAH')->textInput(['type' => 'number', 'min' => ($penguatkuasaan->isNewRecord ? 1 : 0),            
                'style' => 'height:35px; width:200px'
            ]) ?>

            <?= $form->field($penguatkuasaan, 'LONGITUDE')->textInput(['id' => 'long']) ?>

        </div>
    </div>

    <!-- buttons -->
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['ptp/penguatkuasaan', 'nosiri' => $penguatkuasaan->NOSIRI], [
                    'class' => 'btn btn-default',
                ]) ?>

                <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                    'id' => 'action_save2close', 'class' => 'btn btn-success', 'name' => 'action[save2close]', 'value' => 1,
                    'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
                ]) ?>
            </span>
        </div>
    </div>

    <?php ActiveForm::end() ?>
</div>