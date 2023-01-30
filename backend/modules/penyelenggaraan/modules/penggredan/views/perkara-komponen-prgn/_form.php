<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use common\utilities\OptionHandler;
use yii\helpers\ArrayHelper;

use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use backend\modules\penyelenggaraan\models\Perkara;
use backend\modules\penyelenggaraan\models\PerkaraKomponen;
use backend\modules\penyelenggaraan\models\PerkaraKomponenPrgn;

/* @var $this yii\web\View */
/* @var $model backend\modules\parameter\models\KesalahanButir */
/* @var $form yii\widgets\ActiveForm */


$data['KODPERKARA'] = [];
$data['KODKOMPONEN'] = [];

  // set data to display on dropdown field
if (!$model->isNewRecord) {
    if ($model->kodperkara0) {
        $data['KODPERKARA'] = [$model->KODPERKARA => $model->kodperkara0->KODPERKARA . ' - ' . $model->kodperkara0->PRGN];
    }

    if ($model->kodkomponen0) {
        $data['KODKOMPONEN'] = [$model->KODKOMPONEN => $model->kodkomponen0->KODKOMPONEN . ' - ' .$model->kodkomponen0->PRGN];
    }
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
            'permissions' => ['save2close' => Yii::$app->access->can('keterangan-write')],
        ]) ?>

        
            <?= $form->field($model, 'JENIS')->dropDownList(OptionHandler::render('premis-tandas'), ['prompt' => '', 'id'=>'jenis','disabled' => !$model->isNewRecord]) ?>
            
            <?= $form->field($model, 'KODPERKARA')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => $data['KODPERKARA'],
                'options' => ['id'=>'kodperkara'],
                'disabled' => (!$model->isNewRecord)? true : false,
                'pluginOptions'=>[
                    'depends'=>['jenis'],
                    'placeholder' => '',
                    'url' => Url::to(['/option/penyelenggaraan/kod-perkara']),
                ]
            ]); 
            ?>

            <?= $form->field($model, 'KODKOMPONEN')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => $data['KODKOMPONEN'],
                'options' => ['id' => 'kodkomponen'],
                'disabled' => (!$model->isNewRecord)? true : false,
                'pluginOptions' => [
                    'depends' => ['jenis','kodperkara'],
                    'placeholder' => '',
                    'url' => Url::to(['/option/penyelenggaraan/kod-komponen'])
                ]
            ]); 
            ?>
            
            <?= $form->field($model, 'KODPRGN')->textInput(['disabled' => !$model->isNewRecord]) ?>
        
            <?= $form->field($model, 'MARKAH')->textInput(['disabled' => false]) ?>
           
            <?= $form->field($model, 'PRGN')->textArea(['maxlength' => true, 'style' => 'height:140px']) ?>


        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8 action-buttons">
                <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', Url::previous('penggredan/perkara-komponen-prgn'), [
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
