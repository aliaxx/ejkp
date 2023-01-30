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
use backend\modules\makanan\models\BarangSitaan;

$this->title = 'Barang Sitaan';
$this->params['breadcrumbs'] = [
'Mutu Makanan',
['label' => 'Sitaan', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/makanan/sitaan/sitaan', 'nosiri' => $model->NOSIRI]],
];




$option['initialPreview'] = [];
$option['initialPreviewConfig'] = [];

if (!$sitaan->isNewRecord) {
    // get attachments
    if ($files = $sitaan->getAttachments()) {
        foreach ($files as $key => $file) {
            $option['initialPreview'][] = $file;
            $option['initialPreviewConfig'][] = [
                //'size' => filesize(Yii::getAlias('@backend/web/' . Sitaan::IMAGE_PATH . '/' . basename($file))),
                'downloadUrl' => Yii::getAlias('@web/' . BarangSitaan::IMAGE_PATH . '/' . basename($file)),
                'url' => Url::to([
                    '/makanan/sitaan/file-delete',
                    'nosiri' => $sitaan->NOSIRI,
                    'filename' => basename($file),
                ]),
            ];
        }
    }
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

<div class="row">
    <?php $form = ActiveForm::begin(['method' => 'post', 'options'=> ['target' => '_blank']]); ?>
        <?= \codetitan\widgets\ActionBar::widget([
            'target' => 'primary-grid',
            'permissions' => ['new' => Yii::$app->access->can('SDR-write')],
        ]) ?> 

        <div class="action-buttons">
            <?= $this->render('_print') ?>
        </div>
    <?php ActiveForm::end(); ?>
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
            // 'NOSIRI',
            'ID',
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
                    'view' =>   \Yii::$app->access->can('SDR-read'),
                    'update' => \Yii::$app->access->can('SDR-write'),
                    'delete' => \Yii::$app->access->can('SDR-write'),
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>
        
    <br>
    <?php if($sitaan->isNewRecord): ?>
        <h4 style="margin-top:20px;">Daftar Rekod Barang Sitaan</h4>
    <?php else : ?>
        <h4 style="margin-top:20px;">Kemaskini Rekod Barang Sitaan
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar Rekod Baru', ['/makanan/sitaan/sitaan', 'nosiri' => $sitaan->NOSIRI], [
                    'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
                    'aria-label' => 'Daftar', 'data-pjax' => 0,
        ]) ?>
        </h4>
    <?php endif; ?>

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
    <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8">
            <!-- UPLOAD SECTION  -->
                <?= FileInput::widget([
                'model' => $sitaan,
                'attribute' => 'files[]',
                'options' => [
                    'multiple' => true,
                    'accept' => 'image/*',
                ],
                'pluginOptions' => [
                    'allowedFileTypes' => ['image'], // allow only images
                    'language' => 'ms',
                    'dropZoneTitle' => 'Tarik & Letakkan Gambar Anda Di Sini',
                    // 'uploadUrl' => Url::to(['/makanan/sitaan/file-upload']),
                    'uploadUrl' => Url::to(['/makanan/sitaan/file-upload', 'idbarang' => $sitaan->ID]), //pass parameter idsampel to get current id
                    'uploadExtraData' => ['NOSIRI' => $sitaan->NOSIRI],
                    'uploadAsync' => true,
                    // 'maxFileCount' => 4,
                    'maxFileSize' => 2 * 1024 * 1024,
                    'overwriteInitial' => false,
                    'validateInitialCount' => true,
                    'initialPreviewAsData' => true,
                    'initialPreview' => $option['initialPreview'],
                    'initialPreviewConfig' => $option['initialPreviewConfig'],
                    'purifyHtml' => true,
                    'showCaption' => false,
                    'showRemove' => false,
                    'showUpload' => false,
                    'browseClass' => 'btn btn-primary btn-block',
                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                    'browseLabel' =>  'Pilih Gambar',
                    'fileActionSettings' => [
                        'showZoom' => true,
                        'showDrag' => false,
                    ],
                ],
                'pluginEvents' => [
                    'filebatchselected' => "function(event, files) {
                        $(this).fileinput('upload');
                    }",
                    'fileuploaderror' => "function(event, data, msg) {
                        alert(msg);
                    }",
                ],
            ]) ?>
            </div>
        </div>

        <!-- buttons -->
        <div class="row">
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['sitaan/sitaan', 'nosiri' => $model->NOSIRI], [
                            'class' => 'btn btn-default',
                    ]) ?>

                    <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                        'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1,
                        'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
                    ]) ?>
                </span>
            </div>
        </div>

    <?php ActiveForm::end() ?>

    <hr />
</div>


<?php

