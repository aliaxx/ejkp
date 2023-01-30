<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Peranan */
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
            'permissions' => ['save2close' => Yii::$app->access->can('peranan-write')],
        ]) ?>

        <?= $form->field($model, 'NAMAPERANAN')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8 action-buttons">
                <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', Yii::$app->request->referrer ?: ['index'], [
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
