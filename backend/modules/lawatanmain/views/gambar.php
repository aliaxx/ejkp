<?php

use backend\modules\lawatanmain\models\LawatanMain;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;

/**
 * @var View $this
 * @var Datakes $model
 */

$this->title = 'Gambar';
$this->params['breadcrumbs'] = [
$titleMain,
['label' => $title, 'url' => ['index']],
$model->NOSIRI,
$this->title,
];

//Untuk gambar
$option['initialPreview'] = [];
$option['initialPreviewConfig'] = [];

if (!$model->isNewRecord) {
    $idmodule = $model->IDMODULE;
    
    if ($files = $model->getAttachments($idmodule)) {
        foreach ($files as $key => $file) {
            $option['initialPreview'][] = $file;
            $option['initialPreviewConfig'][] = [
              // 'downloadUrl' => Yii::getAlias('@web/'. LawatanMain::IMAGE_PATH_PKK . '/' . basename($file)),
                'downloadUrl' => Yii::getAlias('@web/images/'. $idmodule . '/' . basename($file)),
                'url' => Url::to([
                    '/lawatanmain/lawatan-main/file-delete',
                    'idmodule' => $idmodule,
                    'nosiri' => $model->NOSIRI,
                    'filename' => basename($file),
                ]),
            ];
        }
    }
}
?>

    <?php $form = ActiveForm::begin(); ?>
    <div style="margin-top:61px;"></div>
    <?php ActiveForm::end(); ?>

<!-- ======================================================= Tab Menu ============================================= -->
    <!-- Mutu Makanan -->
    <?php if ($model->IDMODULE == 'PKK'): ?>
        <?=   $this->render('/kolam/extra/_tab', [
            'model' => $model
        ]) ?>

    <!-- Peniaga Kecil & Penjaja -->
    <?php elseif ($model->IDMODULE == 'KPN'): ?>
        <?=   $this->render('/kawalan-perniagaan/extra/_tab', [
            'model' => $model
        ]) ?>

    <!-- Premis Makanan -->
    <?php elseif ($model->IDMODULE == 'PPM'): ?>
    <?=   $this->render('/penggredan-premis/extra/_tab', [
        'model' => $model
    ]) ?>

    <!-- Pencegahan Vektor -->
    <?php elseif ($model->IDMODULE == 'SRT'): ?>
    <?=   $this->render('/srt/extra/_tab', [
        'model' => $model
    ]) ?>
    <?php elseif ($model->IDMODULE == 'ULV'): ?>
    <?=   $this->render('/ulv/extra/_tab', [
        'model' => $model
    ]) ?>
    <?php elseif ($model->IDMODULE == 'PTP'): ?>
    <?=   $this->render('/ptp/extra/_tab', [
        'model' => $model
    ]) ?>
    <?php elseif ($model->IDMODULE == 'LVC'): ?>
    <?=   $this->render('/lvc/extra/_tab', [
        'model' => $model
    ]) ?>
    <?php elseif ($model->IDMODULE == 'PTS'): ?>
    <?=   $this->render('/tandas/extra/_tab', [
        'model' => $model
    ]) ?>
    <?php endif ?>
<!-- ======================================================= End Tab Menu ============================================= -->

    <br><br>

        <?= FileInput::widget([
            'model' => $model,
            'attribute' => 'files[]',
            'options' => [
                'multiple' => true,
                'accept' => 'image/*',
            ],
            'pluginOptions' => [
                'allowedFileTypes' => ['image'], // allow only images
                'language' => 'ms',
                'dropZoneTitle' => 'Tarik & Letakkan Gambar Anda Di Sini',
                'uploadUrl' => Url::to(['/lawatanmain/lawatan-main/file-upload']),
                'uploadExtraData' => ['NOSIRI' => $model->NOSIRI],
                'uploadAsync' => true,
                //'maxFileCount' => 4,
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
