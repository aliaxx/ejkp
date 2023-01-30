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
// exit();

$this->title = 'Barang Sitaan';
$this->params['breadcrumbs'] = [
    ['label' => 'Sitaan', 'url' => ['index']],
    $model->NOSIRI,
    $this->title,
];


$option['initialPreview'] = [];
$option['initialPreviewConfig'] = [];

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

<div>
    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>


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
            'JENISMAKANAN',
            'PENGENALAN',
            'KUANTITI',
            'HARGA',
            'KESALAHAN',
            'NAMAPEMBUAT',
            'ALAMATPEMBUAT',
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
                    'view' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['sitaan-view', 'nosiri' => $model->NOSIRI, 'ID' => $model->ID]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['sitaan', 'nosiri' => $model->NOSIRI, 'idbarang' => $model->ID]); //'nosiri', 'idsampel' are parameter in controller action -NOR06092022
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['sitaan-delete'], [
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan dipadamkan. Maklumat sitaan akan dikemaskini mengikut tarikh tindakan terkini. Teruskan.', //display onclick -NOR120922
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $model->NOSIRI, 'idbarang' => $model->ID,
                            ],
                        ]);
                    },

                    // 'delete' => function ($url, $model) {
                    //     return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['sitaan-delete'], [
                    //         'data-method' => 'post',
                    //         'data-params' => [
                    //             'nosiri' => $model->NOSIRI, 'ID' => $model->ID,
                    //         ],
                    //     ]);
                    // },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('sitaan-read'),
                    'update' => \Yii::$app->access->can('sitaan-write'),
                    'delete' => \Yii::$app->access->can('sitaan-write'),
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>
        
    <h4 style="margin-top:0px;">Daftar sitaan Makanan</h4>
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:20px'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <div class="row">
        <!-- left side -->
        <div class="col-md-6">
            <?= $form->field($sitaan, 'JENISMAKANAN')->textInput(['maxlength' => true])?>

            <?= $form->field($sitaan, 'KUANTITI')->textInput(['type' => 'number', 'min' => ($sitaan->isNewRecord ? 1 : 0)]) ?>

            <?= $form->field($sitaan, 'KESALAHAN')->textInput(['maxlength' => true]) ?>

            <?= $form->field($sitaan, 'ALAMATPEMBUAT')->textarea(['rows' => '3']) ?>

        </div>

        <!-- right side -->
        <div class="col-md-6">
            <?= $form->field($sitaan, 'PENGENALAN')->textInput(['maxlength' => true]) ?>

            <?= $form->field($sitaan, 'HARGA')->textInput(['maxlength' => true]) ?>

            <?= $form->field($sitaan, 'NAMAPEMBUAT')->textInput(['maxlength' => true]) ?>

            <?= $form->field($sitaan, 'CATATAN')->textarea(['rows' => '3']) ?>

        </div>
    </div>

    <h4 style="margin-top:0px;"><?= Yii::t('app', 'Gambar Sitaan') ?></h4>
        <?= $form->field($sitaan, 'image')->widget(FileInput::classname(), [
            'options'=>['accept'=>'image/*'],
            'pluginOptions'=>[
                'allowedFileExtensions'=>['jpg','gif','png'],
                'browseLabel' =>  'Pilih Gambar',
            ]
        ]); ?>

        <!-- button simpan -->
        <div class="row">
            <div class="col-md-offset-10 col-md-2">
                <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                    'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1,
                    'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
                ]) ?>
            </div>
        </div>

    <?php ActiveForm::end() ?>

    <hr />
</div>


<?php

