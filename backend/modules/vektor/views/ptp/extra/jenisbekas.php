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

$this->title = 'Jenis Bekas';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
['label' => 'Pemeriksaan Tempat Pembiakan Aedes(PTP)', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/vektor/ptp/penguatkuasaan', 'nosiri' => $model->NOSIRI]],
];

$url['kawasan'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '25']);
$initVal['kawasan'] = null;
if ($jenis->KAWASAN) {
    $object = ParamDetail::findOne(['KODDETAIL' => $jenis->KAWASAN, 'KODJENIS' => '25']);
    $initVal['kawasan'] = $object->PRGN;
}

$url['keputusan'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '27']);
$initVal['keputusan'] = null;
if ($jenis->KEPUTUSAN) {
    $object = ParamDetail::findOne(['KODDETAIL' => $jenis->KEPUTUSAN, 'KODJENIS' => '27']);
    $initVal['keputusan'] = $object->PRGN;
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

    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['penguatkuasaan', 'nosiri' => $jenis->NOSIRI], [
          'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]',
          'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
            ]) ?>        
    </div>

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
            'JENISBEKAS',
            'BILBEKAS', 
            'BILPOTENSI', 
            'BILPOSITIF', 
            'PURPA', 
            'KAWASAN', 
            [
                'attribute' => 'KAWASAN',
                'value' => 'liputan.PRGN',
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
                    'view' => function ($url, $jenis) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open" style="color:green"></i>', ['jenis-bekas-view', 'nosiri' => $jenis->NOSIRI, 'nosampel' => $jenis->NOSAMPEL, 'ID' => $jenis->ID]);
                    },
                    'update' => function ($url, $jenis) {
                        return Html::a('<i class="glyphicon glyphicon-pencil" style="color:green"></i>', ['jenis-bekas', 'nosiri' => $jenis->NOSIRI, 'nosampel' => $jenis->NOSAMPEL, 'idjenis' => $jenis->ID]); //'nosiri', 'idsampel' are parameter in controller action -NOR06092022
                    },
                    'delete' => function ($url, $jenis) {
                        return Html::a('<i class="glyphicon glyphicon-trash" style="color:green"></i>', ['jenis-bekas-delete'], [
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan dipadamkan. Maklumat jenis akan dikemaskini mengikut tarikh tindakan terkini. Teruskan?', //display onclick -NOR120922
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $jenis->NOSIRI, 'nosampel' => $jenis->NOSAMPEL, 'idjenis' => $jenis->ID, 
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
        <?php if($jenis->isNewRecord): ?>
            <h4 style="margin-top:20px;">Daftar Rekod Jenis Bekas</h4>
        <?php else : ?>
            <h4 style="margin-top:20px;">Kemaskini Rekod Jenis Bekas
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar Rekod Baru', ['/vektor/ptp/jenis-bekas', 'nosiri' => $jenis->NOSIRI, 'nosampel' => $jenis->NOSAMPEL], [
                        'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
                        'aria-label' => 'Daftar', 'data-pjax' => 0,
            ]) ?>
            </h4>    
        <?php endif; ?>

        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:20px'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-7\">{input}\n{error}</div>",
                'labelOptions' => ['class' => 'col-lg-4 control-label'],
            ],
        ]); ?>

    <div class="row">
        <!-- left side -->
        <div class="col-md-6">
            <?= $form->field($jenis, 'JENISBEKAS')->textInput(['maxlength' => true]) ?>
     
            <?= $form->field($jenis, 'BILPOTENSI')->textInput(['type' => 'number']) ?> 
            
            <?= $form->field($jenis, 'KEPUTUSAN')->widget(Select2::className(), [
            'initValueText' => $initVal['keputusan'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['keputusan'],
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

            <?= $form->field($jenis, 'KAWASAN')->widget(Select2::className(), [
            'initValueText' => $initVal['kawasan'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['kawasan'],
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

        </div>

        <!-- right side -->
        <div class="col-md-6">
            <?= $form->field($jenis, 'BILBEKAS')->textInput(['type' => 'number']) ?>
            
            <?= $form->field($jenis, 'BILPOSITIF')->textInput(['type' => 'number']) ?>
                        
            <?= $form->field($jenis, 'PURPA')->textInput(['type' => 'number']) ?>

        </div>
    </div>

    <!-- buttons -->
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['ptp/jenis-bekas', 'nosiri' => $jenis->NOSIRI, 'nosampel' => $jenis->NOSAMPEL], [
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