<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

use kartik\widgets\Select2;
use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

// if ($model->gambar) {
//     $model->gambar = 'images/pengguna/'.$model->gambar;
// }


//to display dropdown subunit -NOR26082022
$source = Yii::$app->db->createCommand("SELECT ID, PRGN FROM TBSUBUNIT WHERE status=1 ORDER BY PRGN")->queryAll();
$data['subunit'] = ArrayHelper::map($source, 'ID', 'PRGN');

// $url['unit-subunit'] = Url::to(['/option/penyelenggaraan/unit-subunit', 'KODJENIS' => '23']);

// $source = Yii::$app->db->createCommand("SELECT KODDETAIL, PRGN FROM TBPARAMETER_DETAIL WHERE status=1 AND KODJENIS =23 ORDER BY PRGN")->queryAll();
// $data['unit'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');


// $output['results'][] = ['id' => $data->ID, 'text' => $result->ID . ' - ' . $result->PRGN];

?>

<div>
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= \codetitan\widgets\ActionBar::widget([
            'permissions' => ['save2close' => Yii::$app->access->can('pengguna-write')],
        ]) ?>

        <?= $form->field($model, 'USERNAME')->textInput(['maxlength' => true, 'disabled' => true]) ?>

        <?= $form->field($model, 'CUSTOMERID')->textInput(['maxlength' => true, 'disabled' => true]) ?>

        <?= $form->field($model, 'NAMA')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'NOKP')->textInput(['maxlength' => true, 'disabled' => true]) ?>

        <?= $form->field($model, 'EMAIL')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'SUBUNIT')->widget(Select2::className(), [
        'data' => $data['subunit'],
        'options' => [
            'placeholder' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            ]
            ])
        ?>
        
        <?= $form->field($model, 'PERANAN')->dropDownList(OptionHandler::render('PERANAN'), ['prompt' => '']) ?>

        <!-- <?= $form->field($model, 'DATA_FILTER')->dropDownList(OptionHandler::render('DATA_FILTER'), ['prompt' => '']) ?> -->

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
