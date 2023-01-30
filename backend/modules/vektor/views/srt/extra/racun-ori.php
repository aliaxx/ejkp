<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
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

use backend\modules\makanan\utilities\OptionHandler;
use backend\modules\penyelenggaraan\models\ParamDetail;
// var_dump();
// exit();

$this->title = $model->NOSIRI;
$this->params['breadcrumbs'] = [
    ['label' => 'Pencegahan Vektor', 'url' => ['index']],
    'SRT',
    'Penggunaan Racun',
    $this->title,
];

$url['penggunaan-racun'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '25']);
$initVal['penggunaan-racun'] = null;
if ($racun->ID_PENGGUNAANRACUN) {
    $object = ParamDetail::findOne(['KODDETAIL' => $racun->ID_PENGGUNAANRACUN, 'KODJENIS' => '25']);
    $initVal['penggunaan-racun'] = $object->PRGN;
}

$url['jenis-racun'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '10']);
$initVal['jenis-racun'] = null;
if ($racun->ID_JENISRACUNSRTULV) {
    $object = ParamDetail::findOne(['KODDETAIL' => $racun->ID_JENISRACUNSRTULV, 'KODJENIS' => '10']);
    $initVal['jenis-racun'] = $object->PRGN;
}

$url['jenis-pelarut'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '16']);
$initVal['jenis-pelarut'] = null;
if ($racun->ID_JENISPELARUT) {
    $object = ParamDetail::findOne(['KODDETAIL' => $racun->ID_JENISPELARUT, 'KODJENIS' => '16']);
    $initVal['jenis-pelarut'] = $object->PRGN;
}

// var_dump($racun->ID_PENGGUNAANRACUN);
// exit();

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

 /* .div1{
         text-align:left; 
         float: left;
         width:50%;
   } */

/* img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 40%;
} */

</style>

<div>
    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>
</div>

<div style="padding-top:20px">
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
            // 'NOSIRI' => 'ST',
            [
                'attribute' => 'ID_PENGGUNAANRACUN',
                'value' => 'penggunaanRacun.PRGN', 
            ],
            [
                'attribute' => 'ID_JENISRACUNSRTULV',
                'value' => 'jenisRacun.PRGN', 
            ],
            [
                'attribute' => 'ID_JENISPELARUT',
                'value' => 'jenisPelarut.PRGN', 
            ],
            'BILCAJ',
            'BILMESIN',
            [
                'format' => 'decimal',
                'attribute' => 'AMAUNRACUN',
            ],
            [
                'format' => 'decimal',
                'attribute' => 'AMAUNPELARUT',
            ],
            [
                'format' => 'decimal',
                'attribute' => 'AMAUNPETROL',
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
                'template' => "{view} {update} {delete}",
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open" style="color:green"></i>', ['racun-view', 'nosiri' => $model->NOSIRI, 'ID' => $model->ID]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-pencil" style="color:green"></i>', ['racun', 'nosiri' => $model->NOSIRI, 'idracun' => $model->ID]); //'nosiri', 'idsampel' are parameter in controller action -NOR06092022
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-trash" style="color:green"></i>', ['racun-delete'], [
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan dipadamkan. Maklumat racun akan dikemaskini mengikut tarikh tindakan terkini. Teruskan?', //display onclick -NOR120922
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $model->NOSIRI, 'idracun' => $model->ID,
                            ],
                        ]);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('racun-read'),
                    'update' => \Yii::$app->access->can('racun-write'),
                    'delete' => \Yii::$app->access->can('racun-write'),
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>
        
    <h4 style="margin-top:0px;">Daftar Penggunaan Racun</h4>
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:0px'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <div class="row">
        <!-- left side -->
        <div class="col-md-6">
            <?= $form->field($racun, 'ID_PENGGUNAANRACUN')->widget(Select2::className(), [
            'initValueText' => $initVal['penggunaan-racun'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['penggunaan-racun'],
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

            <?= $form->field($racun, 'ID_JENISRACUNSRTULV')->widget(Select2::className(), [
            'initValueText' => $initVal['jenis-racun'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['jenis-racun'],
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
            // 'options' => ['disabled' => !$model->isNewRecord],
            ]) ?>
            
            <?= $form->field($racun, 'ID_JENISPELARUT')->widget(Select2::className(), [
            'initValueText' => $initVal['jenis-pelarut'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['jenis-pelarut'],
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
            // 'options' => ['disabled' => !$model->isNewRecord],
            ]) ?>

            <?= $form->field($racun, 'BILCAJ')->textInput(['type' => 'number', 'min' => ($racun->isNewRecord ? 1 : 0)])?>

            <?= $form->field($racun, 'BILMESIN')->textInput(['type' => 'number', 'min' => ($racun->isNewRecord ? 1 : 0)])?>
        </div>

        <!-- right side -->
        <div class="col-md-6">
            <!-- to display field with quantity decimal and display as decimal -NOR28092022  -->
            <?= $form->field($racun, 'AMAUNRACUN', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($racun->AMAUNRACUN)]])->textInput(['type' => 'number', 'step' => '0.01', 'min' => 0])?>

            <?= $form->field($racun, 'AMAUNPELARUT', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($racun->AMAUNPELARUT)]])->textInput(['type' => 'number', 'step' => '0.01', 'min' => 0])?>

            <?= $form->field($racun, 'AMAUNPETROL', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($racun->AMAUNPETROL)]])->textInput(['type' => 'number',  'step' => '0.01', 'min' => 0])?>
        </div>
    </div>

    <!-- button simpan -->
        <div class="row">
            <div class="col-md-offset-10 col-md-2">
                <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                    'id' => 'action_save2close', 'class' => 'btn btn-success', 'name' => 'action[save2close]', 'value' => 1,
                    'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
                ]) ?>
            </div>
        </div>

    <?php ActiveForm::end() ?>

    <hr />
</div>


<?php

