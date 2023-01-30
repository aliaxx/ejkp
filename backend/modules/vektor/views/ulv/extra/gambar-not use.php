<?php

use backend\modules\lawatanmain\models\LawatanMain;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;

/**
 * @var View $this
 * @var Ulv $model
 */

$this->title = 'Gambar';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
['label' =>'Semburan Kabus Mesin (ULV)', 'url' => ['index']],
$model->NOSIRI,
$this->title,
];


//Untuk gambar
$option['initialPreview'] = [];
$option['initialPreviewConfig'] = [];

// if (!$model->isNewRecord) {
    // get attachments
    if ($files = $model->getAttachments('ULV')) {
        foreach ($files as $key => $file) {
            $option['initialPreview'][] = $file;
            $option['initialPreviewConfig'][] = [
                //'size' => filesize(Yii::getAlias('@backend/web/' . Kolam::IMAGE_PATH . '/' . basename($file))),
                'downloadUrl' => Yii::getAlias('@web/' . LawatanMain::IMAGE_PATH_ULV . '/' . basename($file)),
                'url' => Url::to([
                    '/vektor/ulv/file-delete',
                    'idmodule' => 'ULV',
                    'nosiri' => $model->NOSIRI,
                    'filename' => basename($file),
                ]),
            ];
        }
    }
// }
?>

<div style="margin-top:61px;"></div>

<?= $this->render('_tab', [
    'model' => $model,
]) ?>

<br><br>

    <?php $form = ActiveForm::begin(); ?>
    <?php ActiveForm::end(); ?>
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
                'uploadUrl' => Url::to(['/vektor/ulv/file-upload']),
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
                'browseClass' => 'btn btn-success btn-block',
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