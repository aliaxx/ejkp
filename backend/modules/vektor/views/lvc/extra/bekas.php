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

$this->title = 'Bekas Diperiksa';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
['label' => 'Aktiviti Larvaciding', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/vektor/lvc/liputan', 'nosiri' => $model->NOSIRI]],
];

// if ($sampel->isNewRecord) {
//     $sampel->setIdSampel(); //set IDSAMPEL 
// }

$exist = BekasLvc::find()->select('NOSIRI')->where(['NOSIRI' => $bekas->NOSIRI])->exists();

if($exist){
    $ai = $bekas->pencapaian['ai'];
    $ai = bcdiv($ai, 1, 2);

    $bi = $bekas->pencapaian['bi'];
    $bi = bcdiv($bi, 1, 2);

    $ci = $bekas->pencapaian['ci'];
    $ci = bcdiv($ci, 1, 2);
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
<h4>Jenis-Jenis Bekas Diperiksa</h4>
    <?= GridView::widget([
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
            // 'KAWASAN',
            [
                'attribute' => 'KAWASAN',
                // 'headerOptions' => ['style' => 'width:10px'],
                'contentOptions' => ['style' => 'height:40px'],
                'value' => function($bekas) {
                    return OptionHandler::resolve('kaw-pembiakan', $bekas->KAWASAN);
                }
            ],
            'JENISBEKAS',
            'BILBEKAS',
            [
                'attribute' => 'BILPOTENSI',
                'headerOptions' => ['style' => 'height:20px;width:15%'],
            ],
            'BILPOSITIF',
            [
                'attribute' => 'KEPUTUSAN',
                'headerOptions' => ['style' => 'width:15%'],
            ],
            'PURPA',
            'CATATAN',
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
                    'view' => function ($url, $bekas) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open" style="color:green"></i>', ['bekas-view', 'nosiri' => $bekas->NOSIRI, 'ID' => $bekas->ID]);
                    },
                    'update' => function ($url, $bekas) {
                        return Html::a('<i class="glyphicon glyphicon-pencil" style="color:green"></i>', ['bekas', 'nosiri' => $bekas->NOSIRI, 'idbekas' => $bekas->ID]); //'nosiri', 'idsampel' are parameter in controller action -NOR06092022
                    },
                    'delete' => function ($url, $bekas) {
                        return Html::a('<i class="glyphicon glyphicon-trash" style="color:green"></i>', ['bekas-delete'], [
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan dipadamkan. Maklumat bekas akan dikemaskini mengikut tarikh tindakan terkini. Teruskan?', //display onclick -NOR120922
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $bekas->NOSIRI, 'idbekas' => $bekas->ID,
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

    <?= DetailView::widget([
        'model' => $model,
        'template' => "<tr><th style='width: 27.5%;'>{label}</th><td>{value}</td></tr>",
        'attributes' => [
            'PTP_BILBEKASMUSNAH',
        ],
    ]);
    ?>  

    <div class="row">
        <?php if($exist): ?>
            <h4>Pengiraan Pencapaian</h4>
        <!-- <div class="form-group"> -->
            <div class="form-group col-md-7">
                <?= DetailView::widget([
                    'model' => $model,
                    'template' => "<tr><th style='width: 48%;'>{label}</th><td>{value}</td></tr>",
                    'attributes' => [
                        [
                            'label' => '1%Liputan Premis Diperiksa Lengkap (Dalam & Luar Rumah)',
                            'attribute' => 'liputan',
                            // 'value' => $liputan,
                        ],
                        [
                            'label' => 'Indeks Aedes (AI)',
                            'attribute' => 'ai',
                            'value' => $ai,
                        ],
                        [
                            'label' => 'Indeks Breteau (BI)',
                            'attribute' => 'bi',
                            'value' => $bi,
                        ],
                        [
                            'label' => 'Indeks Bekas (CI)',
                            'attribute' => 'ci',
                            'value' => $ci,
                        ],
                        [
                            'label' => 'Indeks Purpa (HPI)',
                            'attribute' => 'hpi',
                            // 'value' => $hpi,
                        ],
                    ],
                    ])
                ?>  
            </div>
        <!-- </div> -->
        <?php endif ?>
    </div>

<div>
    <?php if($bekas->isNewRecord): ?>
        <h4 style="margin-top:20px;">Daftar Rekod Jenis Bekas Diperiksa</h4>
    <?php else : ?>
        <h4 style="margin-top:20px;">Kemaskini Rekod Jenis Bekas Diperiksa
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar Rekod Baru', ['/vektor/ptp/bekas', 'nosiri' => $bekas->NOSIRI], [
                    'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
                    'aria-label' => 'Daftar', 'data-pjax' => 0,
        ]) ?>
        </h4>    
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:20px'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-4 control-label'],
        ],
    ]); ?>

    <div class="row">
        <!-- left side -->
        <div class="col-md-6">
            <?= $form->field($bekas, 'KAWASAN')->dropDownList(OptionHandler::render('kaw-pembiakan'), ['prompt' => '']) ?>

            <?= $form->field($bekas, 'BILBEKAS')->textInput(['type' => 'number', 'min' => 0])?>

            <?= $form->field($bekas, 'BILPOSITIF')->textInput(['type' => 'number', 'min' => 0])?>

            <?= $form->field($bekas, 'CATATAN')->textarea(['rows' => '3']) ?>
        </div>

        <!-- right side -->
        <div class="col-md-6">
            <?= $form->field($bekas, 'JENISBEKAS')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($bekas, 'BILPOTENSI')->textInput(['type' => 'number', 'min' => 0])?>

            <?= $form->field($bekas, 'KEPUTUSAN')->textInput(['maxlength' => true]) ?>

            <?= $form->field($bekas, 'PURPA')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">    
        <h4>Bilangan Bekas Dimusnahkan</h4>
        <div class="col-md-6">
            <?= $form->field($model, 'PTP_BILBEKASMUSNAH')->textInput([
                'type' => 'number', 
                'min' => ($model->isNewRecord ? 1 : 0),            
            ]) ?>
        </div>
    </div>

    <!-- buttons -->
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['lvc/bekas', 'nosiri' => $model->NOSIRI], [
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