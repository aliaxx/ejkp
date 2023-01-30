<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\modules\parameter\models\AsasTindakan */
/* @var $form yii\widgets\ActiveForm */

$url['KODJENIS'] = Url::to(['/option/penyelenggaraan/param-header']);
$initVal['KODJENIS'] = null;
if ($model->KODJENIS) {
    $object = \backend\modules\penyelenggaraan\models\ParamHeader::findOne($model->KODJENIS);
    $initVal['KODJENIS'] = $object->PRGN;
}


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
            'permissions' => ['save2close' => Yii::$app->access->can('param-detail-write')],
        ]) ?>

        <?= $form->field($model, 'KODJENIS')->widget(Select2::className(), [
            'initValueText' => $initVal['KODJENIS'],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => '',
                'ajax' => [
                    'url' => $url['KODJENIS'],
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
            'options' => ['disabled' => !$model->isNewRecord],
        ]) ?>

    <?php if (!$model->isNewRecord): ?>
        <?= $form->field($model, 'KODDETAIL')->textInput(['disabled' => true]) ?>
    <?php endif; ?>

        <?= $form->field($model, 'PRGN')->textArea(['maxlength' => true, 'style' => 'height:140px']) ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8 action-buttons">
                <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', Url::previous('param/param-detail'), [
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
