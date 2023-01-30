<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\SettingForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div>
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= \codetitan\widgets\ActionBar::widget([
        'template' => '{save} {cancel}',
        'permissions' => ['save' => 'setting-update'],
    ]) ?>

        <?= $form->field($model, 'subject_enabled')->widget(SwitchInput::className(), [
            'containerOptions' => ['style' => 'margin-left:0'],
            'pluginOptions' => [
                'size' => 'small',
                'handleWidth' => 30,
                'onText' => 'On',
                'offText' => 'Off',
                'onColor' => 'success',
                'offColor' => 'danger',
            ],
        ]) ?>

        <div class="horizontal-divider"></div>

        <?= $form->field($model, 'matrix_no_format')->textInput(['maxlength' => true]) ?>

    <?php ActiveForm::end(); ?>
</div>
