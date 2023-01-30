<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
// use kartik\widgets\ActiveForm;
use backend\modules\vektor\models\SasaranSrt;
use backend\modules\vektor\models\SasaranSrtSearch;
use backend\modules\penyelenggaraan\models\ParamDetail;


/**
 * @var View $this
 * @var SasaranSrt $model
 * @var int $counter
 * @var array $data
 */
// $data['ID_JENISPREMIS'] = ['' => ''];
$source = ParamDetail::find()->where(['KODJENIS' => 4])->all();
$data['jenis-premis'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

?>
<?php $form = ActiveForm::begin() ?>
<tr id="itemRow_<?= $counter ?>">
    <td>
        <?= $form->field($sasaran, 'ID_JENISPREMIS[]')->widget(Select2::className(), [
            'data' => $data['jenis-premis'],
            'options' => [
                'id' => 'sasaransrt-id_jenispremis_' . $counter,
                'placeholder' => '',
                'style' => 'height:26px; width:200px',
                'value' => isset($tmpData) ? $tmpData['ID_JENISPREMIS'] : null, //display from table sasaransrt -NOR300922
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false) ?>
    </td>
    <td>
        <!-- lable false untuk tidak display lable dalam dan luar box -NOR28092022 -->
        <?= $form->field($sasaran, 'JUMPREMIS[]')->textInput([
            'type' => 'number', 
            'min' => ($sasaran->isNewRecord ? 1 : 0),
            'id' => 'sasaransrt-jumpremis_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:35px; width:200px',
            'value' => isset($tmpData) ? $tmpData['JUMPREMIS'] : null,
        ])->label(false) ?>
    </td>
    <td>
        <?= $form->field($sasaran, 'PENCAPAIAN1[]')->textInput([
            'type' => 'number', 
            'min' => ($sasaran->isNewRecord ? 1 : 0),            
            'id' => 'sasaransrt-pencapaian1_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:35px; width:200px',
            'value' => isset($tmpData) ? $tmpData['PENCAPAIAN1'] : null,
        ])->label(false) ?>
    </td>
    <td>
        <?= $form->field($sasaran, 'PENCAPAIAN2[]')->textInput([
            'type' => 'number', 
            'min' => ($sasaran->isNewRecord ? 1 : 0),           
            'id' => 'sasaransrt-pencapaian2_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:35px; width:200px',
            'value' => isset($tmpData) ? $tmpData['PENCAPAIAN2'] : null,
        ])->label(false) ?>
    </td>

    <td style="width:50px">
        <?php if ($counter > 1) : ?>
            <button id="btnRemoveRow_<?= $counter ?>" type="button" class="btn btn-danger waves-effect" title="<?= Yii::t('app', 'Click to remove') ?>">
                <i class="fa fa-times"></i>
            </button>
        <?php endif; ?>
    </td>
</tr>
<?php
$this->registerJs("
$('.field-sasaransrt-id_jenispremis').toggleClass('required');
$('#sasaransrt-form').yiiActiveForm('add', {
    id: 'sasaransrt-id_jenispremis_$counter',
    name: 'SasaranSrt[id_jenispremis][]',
    container: '.field-sasaransrt-id_jenispremis_$counter',
    input: '#sasaransrt-id_jenispremis_$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Jenis Premis Tidak Boleh Dibiarkan Kosong'});
    }
});

$('.field-sasaransrt-jumpremis').toggleClass('required');
$('#sasaransrt-form').yiiActiveForm('add', {
    id: 'sasaransrt-jumpremis_$counter',
    name: 'SasaranSrt[JUMPREMIS][]',
    container: '.field-sasaransrt-jumpremis_$counter',
    input: '#sasaransrt-jumpremis_$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Jumlah Premis Tidak Boleh Dibiarkan Kosong'});
    }
});


$('.field-sasaransrt-pencapaian1').toggleClass('required');
$('#sasaransrt-form').yiiActiveForm('add', {
    id: 'sasaransrt-pencapaian1_$counter',
    name: 'SasaranSrt[PENCAPAIAN1][]',
    container: '.field-sasaransrt-pencapaian1_$counter',
    input: '#sasaransrt-pencapaian1_$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Pencapaian Tidak Boleh Dibiarkan Kosong'});
    }
});

$('.field-sasaransrt-pencapaian2').toggleClass('required');
$('#sasaransrt-form').yiiActiveForm('add', {
    id: 'sasaransrt-pencapaian2_$counter',
    name: 'SasaranSrt[PENCAPAIAN2][]',
    container: '.field-sasaransrt-pencapaian2_$counter',
    input: '#sasaransrt-pencapaian2_$counter',
    error: '.help-block',
    // validate:  function (attribute, value, messages, deferred) {
    //     yii.validation.required(value, messages, {message: 'Pencapaian Tidak Boleh Dibiarkan Kosong'});
    // }
});

// $(document).ready(function () {
//     $('#sasaran-form').on('click', 'button[id^=\"btnRemoveRow_\"]', function () {
//         let rowToRemove = this.id.split('_').pop();
//         $('#itemRow_' + rowToRemove).remove();
//     });
// });

", yii\web\View::POS_END);
