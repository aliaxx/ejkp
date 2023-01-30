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

$this->title = 'Aktiviti Larvaciding';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
['label' => 'Aktiviti Larvaciding', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/vektor/lvc/larvisid', 'nosiri' => $model->NOSIRI]],
];

$url['racun'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '10']);
$initVal['racun'] = null;
if ($larvisid->V_ID_JENISRACUN) {
    $object = ParamDetail::findOne(['KODDETAIL' => $larvisid->V_ID_JENISRACUN, 'KODJENIS' => '10']);
    $initVal['racun'] = $object->PRGN;
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
        'permissions' => ['new' => Yii::$app->access->can('LVC-write')],
    ]) ?> 

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
            [
                'attribute' => 'AKTIVITI',
                'value' => function($larvisid) {
                    return OptionHandler::resolve('aktiviti', $larvisid->AKTIVITI);
                }
            ],
            'V_SASARANPREMIS',
            'V_BILPREMIS',
            'V_BILBEKAS',
            [
                'attribute' => 'V_ID_JENISRACUN',
                'value' => 'racun.PRGN',
            ],
            'V_JUMRACUN',
            'V_BILMESIN',
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
                    'view' => function ($url, $larvisid) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open" style="color:green"></i>', ['larvisid-view', 'nosiri' => $larvisid->NOSIRI, 'ID' => $larvisid->ID]);
                    },
                    'update' => function ($url, $larvisid) {
                        return Html::a('<i class="glyphicon glyphicon-pencil" style="color:green"></i>', ['larvisid', 'nosiri' => $larvisid->NOSIRI, 'idlarvisid' => $larvisid->ID]); //'nosiri', 'idsampel' are parameter in controller action -NOR06092022
                    },
                    'delete' => function ($url, $larvisid) {
                        return Html::a('<i class="glyphicon glyphicon-trash" style="color:green"></i>', ['larvisid-delete'], [
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan dipadamkan. Maklumat larvisid akan dikemaskini mengikut tarikh tindakan terkini. Teruskan?', //display onclick -NOR120922
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $larvisid->NOSIRI, 'idlarvisid' => $larvisid->ID,
                            ],
                        ]);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('LVC-read'),
                    'update' => \Yii::$app->access->can('LVC-write'),
                    'delete' => \Yii::$app->access->can('LVC-write'),
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
        <?php if($larvisid->isNewRecord): ?>
            <h4 style="margin-top:20px;">Daftar Rekod Aktiviti Larvaciding</h4>
        <?php else : ?>
            <h4 style="margin-top:20px;">Kemaskini Rekod Aktiviti Larvaciding
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar Rekod Baru', ['/vektor/lvc/larvisid', 'nosiri' => $larvisid->NOSIRI], [
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
            <?= $form->field($larvisid, 'AKTIVITI')->dropDownList(OptionHandler::render('aktiviti'), ['prompt' => '']) ?>
        </div>
    </div>
    <div class="row">
        <!-- left side -->
        <div class="col-md-6">
            <?= $form->field($larvisid, 'V_SASARANPREMIS')->textInput(['type' => 'number', 'min' => ($larvisid->isNewRecord ? 1 : 0)]); ?> 
            
            <?= $form->field($larvisid, 'V_BILBEKAS')->textInput(['type' => 'number', 'min' => ($larvisid->isNewRecord ? 1 : 0)]); ?> 
            
            <?= $form->field($larvisid, 'V_JUMRACUN', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($larvisid->V_JUMRACUN)]]); ?>

        </div>

        <!-- right side -->
        <div class="col-md-6">
            <?= $form->field($larvisid, 'V_BILPREMIS')->textInput(['type' => 'number', 'min' => ($larvisid->isNewRecord ? 1 : 0)]) ?> 
            
            <?= $form->field($larvisid, 'V_ID_JENISRACUN')->widget(Select2::className(), [
                'initValueText' => $initVal['racun'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => '',
                    'ajax' => [
                        'url' => $url['racun'],
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
                // 'options' => ['disabled' => !$larvisid->isNewRecord],
                ]) ?>
            
            <?= $form->field($larvisid, 'V_BILMESIN')->textInput(['type' => 'number', 'min' => ($larvisid->isNewRecord ? 1 : 0)]) ?>         
        </div>
    </div>

    <!-- buttons -->
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['lvc/larvisid', 'nosiri' => $larvisid->NOSIRI], [
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