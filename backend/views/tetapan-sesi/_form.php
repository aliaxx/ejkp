<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\TetapanSesi */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Tetapan Sesi';
$this->params['breadcrumbs'] = [
    'Tetapan',
    ['label' => 'Tetapan Sesi', 'url' => ['index']],
];
?>

<div>
    <h4><?= Yii::t('app', 'Tetapan Sesi') ?></h4>

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'jangka_masa')->textInput(['type' => 'number', 'min' => 1, 'maxlength' => true,]) ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8 action-buttons">
                <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Sahkan', [
                    'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1,
                    'title' => 'Sahkan', 'aria-label' => 'Sahkan', 'data-pjax' => 0,
                    'data' => [
                        'confirm' => 'Adakah anda pasti untuk kemaskini jangka masa sesi?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
