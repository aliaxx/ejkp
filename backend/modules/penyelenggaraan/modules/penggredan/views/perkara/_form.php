<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use common\utilities\OptionHandler;

use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use backend\modules\penyelenggaraan\models\Perkara;


/* @var $this yii\web\View */
/* @var $model backend\modules\inventori\models\AsetKategori */
/* @var $form yii\widgets\ActiveForm */
$url['JENIS'] = Url::to(['/option/penyelenggaraan/jenis']);

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
        'permissions' => ['save2close' => Yii::$app->access->can('perkara-write')],
    ]) ?>

 
    <?= $form->field($model, 'JENIS')->dropDownList(OptionHandler::render('premis-tandas'), ['prompt' => '', 'disabled' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'KODPERKARA')->textInput(['disabled' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'PRGN')->textarea(['maxlength' => true, 'style' => 'height:80px']) ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8 action-buttons">
            <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', Url::previous('penggredan/perkara'), [
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