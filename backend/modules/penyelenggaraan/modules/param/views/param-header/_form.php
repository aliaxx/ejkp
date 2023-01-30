<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\parameter\models\TujuanLawatan */
/* @var $form yii\widgets\ActiveForm */
?>

<div>
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= \codetitan\widgets\ActionBar::widget([
            'permissions' => ['save2close' => Yii::$app->access->can('param-header-write')],
        ]) ?>

        <?= $form->field($model, 'PRGN')->textArea(['maxlength' => true, 'style' => 'height:140px']) ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8 action-buttons">
                <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', Url::previous('param/param-header'), [
                    'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]',
                    'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
                ]) ?>

                <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                    'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1,
                    'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
                ]) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
