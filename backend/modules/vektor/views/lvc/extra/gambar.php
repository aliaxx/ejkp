<?php

use backend\modules\makanan\models\Kolam;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use backend\modules\vektor\models\Lvc;

/**
 * @var View $this
 * @var Lvc $model
 */

$this->title = 'Gambar';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
'Larvaciding',
['label' => $this->title, 'url' => ['index']],
$model->NOSIRI,
];


//Untuk gambar
$option['initialPreview'] = [];
$option['initialPreviewConfig'] = [];

// if (!$model->isNewRecord) {
    // get attachments
    if ($files = $model->getAttachments()) {
        foreach ($files as $key => $file) {
            $option['initialPreview'][] = $file;
            $option['initialPreviewConfig'][] = [
                //'size' => filesize(Yii::getAlias('@backend/web/' . Kolam::IMAGE_PATH . '/' . basename($file))),
                'downloadUrl' => Yii::getAlias('@web/' . Lvc::IMAGE_PATH . '/' . basename($file)),
                'url' => Url::to([
                    '/vektor/lvc/file-delete',
                    'nosiri' => $model->NOSIRI,
                    'filename' => basename($file),
                ]),
            ];
        }
    }
// }
?>

<div>
    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>

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
                'uploadUrl' => Url::to(['/vektor/lvc/file-upload']),
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