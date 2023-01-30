<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\modules\penyelenggaraan\models\Lokaliti */
/* @var $form yii\widgets\ActiveForm */
$url['mukim'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '11']);

?>

<div class="lokaliti-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'IDMUKIM')->widget(Select2::className(), [
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['mukim'],
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

    <?= $form->field($model, 'PRGN')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'IDZONAM')->textInput() ?> -->

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-8 action-buttons">
            <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', Url::previous('lokaliti/lokaliti'), [
                'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]',
                'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
            ]) ?>

            <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 0,
                'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
