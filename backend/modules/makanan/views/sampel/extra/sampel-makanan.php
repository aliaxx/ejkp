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
use backend\modules\makanan\models\Makanan;
use backend\modules\makanan\models\SampelMakanan;
// var_dump();
// exit();

$this->title = 'Sampel Makanan';
$this->params['breadcrumbs'] = [
'Mutu Makanan',
['label' => 'Sampel Makanan', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/makanan/sampel/sampel', 'nosiri' => $model->NOSIRI]],
];


// $models = SampelMakanan::findOne(['NOSIRI' => $sampel->NOSIRI, 'ID' => $sampel->ID]);

$option['initialPreview'] = [];
$option['initialPreviewConfig'] = [];

// $models->namagambar = $model->namagambar;
// var_dump($sampel->ID);
// exit();


// if (!$sampel->isNewRecord) {
    // if($sampel->ID){
    // get attachments
    if ($files = $sampel->getAttachments()) {
        foreach ($files as $key => $file) {
            $option['initialPreview'][] = $file;
            $option['initialPreviewConfig'][] = [
                //'size' => filesize(Yii::getAlias('@backend/web/' . Makanan::IMAGE_PATH . '/' . basename($file))),
                'downloadUrl' => Yii::getAlias('@web/' . SampelMakanan::IMAGE_PATH . '/' . basename($file)),
                'url' => Url::to([
                    '/makanan/sampel/file-delete',
                    'nosiri' => $sampel->NOSIRI,
                    'filename' => basename($file),
                ]),
            ];
        }
        // var_dump(basename($file));
        // exit();

    }
    // }
// }

//get current date
date_default_timezone_set("Asia/Kuala_Lumpur");
// Then call the date functions
$date = date('d-m-Y, H:i');
$sampel->TRKHSAMPEL=$date;

if (!$sampel->isNewRecord) {
    $sampel->TRKHSAMPEL = DateTimeHelper::convert($sampel->TRKHSAMPEL, false, true);
}

$url['jenis-analisis'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '19']);
$initVal['jenis-analisis1'] = null;
if ($sampel->ID_JENISANALISIS1) {
    $object = ParamDetail::findOne(['KODDETAIL' => $sampel->ID_JENISANALISIS1, 'KODJENIS' => '19']);
    $initVal['jenis-analisis1'] = $object->PRGN;
}

$initVal['jenis-analisis2'] = null;
if ($sampel->ID_JENISANALISIS2) {
    $object = ParamDetail::findOne(['KODDETAIL' => $sampel->ID_JENISANALISIS2, 'KODJENIS' => '19']);
    $initVal['jenis-analisis2'] = $object->PRGN;
}

$initVal['jenis-analisis3'] = null;
if ($sampel->ID_JENISANALISIS3) {
    $object = ParamDetail::findOne(['KODDETAIL' => $sampel->ID_JENISANALISIS3, 'KODJENIS' => '19']);
    $initVal['jenis-analisis3'] = $object->PRGN;
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

<div style="margin-top:61px;"></div>
<?= $this->render('_tab', ['model' => $model]) ?>
<br>

<div class="row">
    <?php $form = ActiveForm::begin(['method' => 'post', 'options'=> ['target' => '_blank']]); ?>
        <?= \codetitan\widgets\ActionBar::widget([
            'target' => 'primary-grid',
            'permissions' => ['new' => Yii::$app->access->can('SMM-write')],
        ]) ?> 

        <div class="action-buttons"><?= $this->render('_print') ?></div>
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
            'NOSAMPEL',
            'TRKHSAMPEL',
            'JENIS_SAMPEL',
            [
                'attribute' => 'ID_JENISANALISIS1',
                'value' => 'jenisAnalisis1.PRGN', 
                // 'value' => function ($sampel) {
                //     //return $model->kodkatkawasan0->prgn;
                //     $record = Yii::$app->db->createCommand("SELECT KODDETAIL, PRGN FROM TBPARAMETER_DETAIL WHERE KODJENIS=19")->queryOne();
                //     return ($record)? $record['PRGN'] : null;
                // },
            ],
            [
                'attribute' => 'ID_JENISANALISIS2',
                'value' => 'jenisAnalisis2.PRGN', 
            ],
            [
                'attribute' => 'ID_JENISANALISIS3',
                'value' => 'jenisAnalisis3.PRGN', 
            ],
            'JENAMA',
            'HARGA',
            'PEMBEKAL',
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
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['sampel-view', 'nosiri' => $model->NOSIRI, 'ID' => $model->ID]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['sampel', 'nosiri' => $model->NOSIRI, 'idsampel' => $model->ID]); //'nosiri', 'idsampel' are parameter in controller action -NOR06092022
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['sampel-delete'], [
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan dipadamkan. Maklumat sampel akan dikemaskini mengikut tarikh tindakan terkini. Teruskan?', //display onclick -NOR120922
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $model->NOSIRI, 'idsampel' => $model->ID,
                            ],
                        ]);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('SMM-read'),
                    'update' => \Yii::$app->access->can('SMM-write'),
                    'delete' => \Yii::$app->access->can('SMM-write'),
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>
        
    <?php if($sampel->isNewRecord): ?>
        <h4 style="margin-top:20px;">Daftar Rekod Sampel Makanan</h4>
    <?php else : ?>
        <h4 style="margin-top:20px;">Kemaskini Rekod Sampel Makanan 
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar Rekod Baru', ['/makanan/sampel/sampel', 'nosiri' => $sampel->NOSIRI], [
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
            <?= $form->field($sampel, 'NOSAMPEL')->textInput(['maxlength' => true])?>

            <?= $form->field($sampel, 'JENIS_SAMPEL')->textInput(['maxlength' => true]) ?>

            <?= $form->field($sampel, 'ID_JENISANALISIS2')->widget(Select2::className(), [
            'initValueText' => $initVal['jenis-analisis2'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['jenis-analisis'],
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

            <?= $form->field($sampel, 'JENAMA')->textInput(['maxlength' => true]) ?>

            <?= $form->field($sampel, 'PEMBEKAL')->textarea(['rows' => '3']) ?>

        </div>

        <!-- right side -->
        <div class="col-md-6">
            <?= $form->field($sampel, 'TRKHSAMPEL')->textInput()->widget(DateTimePicker::className(), [
                'name' => 'TRKHSAMPEL',
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

            <?= $form->field($sampel, 'ID_JENISANALISIS1')->widget(Select2::className(), [
            'initValueText' => $initVal['jenis-analisis1'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['jenis-analisis'],
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

            <?= $form->field($sampel, 'ID_JENISANALISIS3')->widget(Select2::className(), [
            'initValueText' => $initVal['jenis-analisis3'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['jenis-analisis'],
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
            
            <?= $form->field($sampel, 'HARGA')->textInput(['maxlength' => true]) ?>

            <?= $form->field($sampel, 'CATATAN')->textarea(['rows' => '3']) ?>
        
        </div>
    </div>

    <h4 style="margin-top:0px;"><?= Yii::t('app', 'Gambar Sampel') ?></h4>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8">
            <!-- UPLOAD SECTION  -->
                <?= FileInput::widget([
                'model' => $sampel,
                'attribute' => 'files[]',
                'options' => [
                    'multiple' => true,
                    'accept' => 'image/*',
                ],
                'pluginOptions' => [
                    'allowedFileTypes' => ['image'], // allow only images
                    'language' => 'ms',
                    'dropZoneTitle' => 'Tarik & Letakkan Gambar Anda Di Sini',
                    'uploadUrl' => Url::to(['/makanan/sampel/file-upload', 'idsampel' => $sampel->ID]), //pass parameter idsampel to get current id
                    // 'uploadUrl' => function ($url, $sampel) {
                    // if(!$sampel->isNewRecord){
                    //     return Url::to(['/makanan/sampel/file-upload', 'idsampel' => $sampel->ID]);
                    // }else{
                    //     return Url::to(['/makanan/sampel/file-upload', 'idsampel']);
                    // }
                    // },
                    // 'uploadExtraData' => ['NOSIRI' => $sampel->NOSIRI, 'ID' => $sampel->ID],
                    'uploadExtraData' => ['NOSIRI' => $sampel->NOSIRI],
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
                    <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['sampel/sampel', 'nosiri' => $model->NOSIRI], [
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

