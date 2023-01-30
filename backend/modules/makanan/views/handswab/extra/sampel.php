<?php

use kartik\widgets\Select2;
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


use backend\modules\makanan\utilities\OptionHandler;
use backend\modules\makanan\models\SampelHandswab;

// var_dump();
// exit();

$this->title = 'Sampel Handswab';
$this->params['breadcrumbs'] = [
'Mutu Makanan',
['label' => 'Handswab', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/makanan/handswab/sampel', 'nosiri' => $model->NOSIRI]],
];

// if ($sampel->isNewRecord) {
//     $sampel->setIdSampel(); //set IDSAMPEL 
// }

$option['initialPreview'] = [];
$option['initialPreviewConfig'] = [];

if (!$sampel->isNewRecord) {
// get attachments
    if ($files = $sampel->getAttachments()) {
        foreach ($files as $key => $file) {
            $option['initialPreview'][] = $file;
            $option['initialPreviewConfig'][] = [
                //'size' => filesize(Yii::getAlias('@backend/web/' . Kolam::IMAGE_PATH . '/' . basename($file))),
                'downloadUrl' => Yii::getAlias('@web/' . SampelHandswab::IMAGE_PATH . '/' . basename($file)),
                'url' => Url::to([
                    '/makanan/handswab/file-delete',
                    'nosiri' => $sampel->NOSIRI,
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
            'permissions' => ['new' => Yii::$app->access->can('HSW-write')],
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
            'IDSAMPEL',
            'NAMAPEKERJA',
            'NOKP',
            [
                'attribute' => 'JANTINA',
                'value' => function($sampel) {
                    return OptionHandler::resolve('jantina', $sampel->JANTINA);
                }
            ],
            [
                'attribute' => 'TY2',
                'value' => function($sampel) {
                    return OptionHandler::resolve('ty2-fhc', $sampel->TY2);
                }
            ],
            [
                'attribute' => 'FHC',
                'value' => function($sampel) {
                    return OptionHandler::resolve('ty2-fhc', $sampel->FHC);
                }
            ],
            [
                'attribute' => 'KEPUTUSAN',
                'value' => function($sampel) {
                    return OptionHandler::resolve('keputusan', $sampel->KEPUTUSAN);
                }
            ],
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
                            'data-confirm' => 'Rekod akan dipadamkan. Maklumat sampel akan dikemaskini mengikut tarikh tindakan terkini. Teruskan.', //display onclick -NOR120922
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $model->NOSIRI, 'idsampel' => $model->ID,
                            ],
                        ]);
                    },

                    // 'delete' => function ($url, $model) {
                    //     return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['sampel-delete'], [
                    //         'data-method' => 'post',
                    //         'data-params' => [
                    //             'nosiri' => $model->NOSIRI, 'ID' => $model->ID,
                    //         ],
                    //     ]);
                    // },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('HSW-read'),
                    'update' => \Yii::$app->access->can('HSW-write'),
                    'delete' => \Yii::$app->access->can('HSW-write'),
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>
        
    <?php if($sampel->isNewRecord): ?>
        <h4 style="margin-top:20px;">Daftar Rekod Sampel Handswab</h4>
    <?php else : ?>
        <h4 style="margin-top:20px;">Kemaskini Rekod Sampel Handswab
        &nbsp;&nbsp;&nbsp;&nbsp;
        <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar Rekod Baru', ['/makanan/handswab/sampel', 'nosiri' => $sampel->NOSIRI], [
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
            <!-- <?= $form->field($sampel, 'IDSAMPEL')->textInput(['disabled' => true])?> set and display idsampel -->
            <?= $form->field($sampel, 'IDSAMPEL')->textInput(['maxlength' => true])?>

            <?= $form->field($sampel, 'NOKP')->textInput(['maxlength' => true]) ?>

            <?= $form->field($sampel, 'TY2')->radioList(OptionHandler::render('ty2-fhc'), ['selector'=>'radio', 'inline'=>true]); ?>

            <?= $form->field($sampel, 'KEPUTUSAN')->radioList(OptionHandler::render('keputusan'), ['selector'=>'radio', 'inline'=>true]); ?>
        </div>

        <!-- right side -->
        <div class="col-md-6">
            <?= $form->field($sampel, 'NAMAPEKERJA')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($sampel, 'JANTINA')->radioList(OptionHandler::render('jantina'), ['selector'=>'radio', 'inline'=>true]); ?>

            <?= $form->field($sampel, 'FHC')->radioList(OptionHandler::render('ty2-fhc'), ['selector'=>'radio', 'inline'=>true]); ?>

            <?= $form->field($sampel, 'CATATAN')->textarea(['rows' => '3']) ?>
        </div>

        <h4 style="margin-top:0px;"><?= Yii::t('app', 'Gambar Sampel') ?></h4>
        <!-- <h5><?= Yii::t('app', 'Gambar') ?></h5> -->
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
                    // 'uploadUrl' => Url::to(['/makanan/handswab/file-upload']),
                    'uploadUrl' => Url::to(['/makanan/handswab/file-upload', 'idsampel' => $sampel->ID]), //pass parameter idsampel to get current id
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
                    <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['handswab/sampel', 'nosiri' => $model->NOSIRI], [
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

