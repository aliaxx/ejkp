<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
// use kartik\widgets\ActiveForm;
// use backend\modules\vektor\models\SasaranPtp;
// use backend\modules\vektor\models\SasaranSrtSearch;
use backend\modules\penyelenggaraan\models\ParamDetail;


/**
 * @var View $this
 * @var SasaranLvc $model
 * @var int $counter
 * @var array $data
 */
// $data['ID_JENISPREMIS'] = ['' => ''];
$source = ParamDetail::find()->where(['KODJENIS' => 8])->all();
$data['jenis-premis'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

// var_dump($data['jenis-premis']);
// exit();

?>
<?php $form = ActiveForm::begin() ?>
<tr id="itemRow_<?= $counter ?>">
    <td>
        <?= $form->field($sasaran, 'ID_JENISPREMIS[]')->widget(Select2::className(), [
            'data' => $data['jenis-premis'],
            'options' => [
                'id' => 'sasaranlvc-id_jenispremis_' . $counter,
                'placeholder' => '',
                'style' => 'height:26px; width:200px',
                'value' => isset($tmpData) ? $tmpData['ID_JENISPREMIS'] : null, //display from table sasaranlvc -NOR300922
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false) ?>
    </td>
    <td>
        <!-- lable false untuk tidak display lable dalam dan luar box -NOR28092022 -->
        <?= $form->field($sasaran, 'SASARAN[]')->textInput([
            'type' => 'number', 
            // 'min' => ($sasaran->isNewRecord ? 1 : 0),
            'id' => 'sasaranlvc-sasaran_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:35px; width:200px',
            'value' => isset($tmpData) ? $tmpData['SASARAN'] : null,
        ])->label(false) ?>
    </td>
    <td>
        <?= $form->field($sasaran, 'PENCAPAIAN[]')->textInput([
            'type' => 'number', 
            // 'min' => ($sasaran->isNewRecord ? 1 : 0),            
            'id' => 'sasaranlvc-pencapaian_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:35px; width:200px',
            'value' => isset($tmpData) ? $tmpData['PENCAPAIAN'] : null,
        ])->label(false) ?>
    </td>
    <td>
        <!-- field for peratusan - tbc -->
    </td>
    <td>
        <?= $form->field($sasaran, 'JUMPOSITIF[]')->textInput([
            'type' => 'number', 
            // 'min' => ($sasaran->isNewRecord ? 1 : 0),            
            'id' => 'sasaranlvc-jumpositif_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:35px; width:200px',
            'value' => isset($tmpData) ? $tmpData['JUMPOSITIF'] : null,
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
$('.field-sasaranlvc-id_jenispremis').toggleClass('required');
$('#sasaranlvc-form').yiiActiveForm('add', {
    id: 'sasaranlvc-id_jenispremis_$counter',
    name: 'SasaranPtp[id_jenispremis][]',
    container: '.field-sasaranlvc-id_jenispremis_$counter',
    input: '#sasaranlvc-id_jenispremis_$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Jenis Premis Tidak Boleh Dibiarkan Kosong'});
    }
});

$('.field-sasaranlvc-sasaran').toggleClass('required');
$('#sasaranlvc-form').yiiActiveForm('add', {
    id: 'sasaranlvc-sasaran_$counter',
    name: 'SasaranPtp[SASARAN][]',
    container: '.field-sasaranlvc-sasaran_$counter',
    input: '#sasaranlvc-sasaran_$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Jumlah Premis Tidak Boleh Dibiarkan Kosong'});
    }
});


$('.field-sasaranlvc-pencapaian').toggleClass('required');
$('#sasaranlvc-form').yiiActiveForm('add', {
    id: 'sasaranlvc-pencapaian_$counter',
    name: 'SasaranPtp[PENCAPAIAN][]',
    container: '.field-sasaranlvc-pencapaian_$counter',
    input: '#sasaranlvc-pencapaian_$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Pencapaian Tidak Boleh Dibiarkan Kosong'});
    }
});

$('.field-sasaranlvc-jumpositif').toggleClass('required');
$('#sasaranlvc-form').yiiActiveForm('add', {
    id: 'sasaranlvc-jumpositif_$counter',
    name: 'SasaranPtp[JUMPOSITIF][]',
    container: '.field-sasaranlvc-jumpositif_$counter',
    input: '#sasaranlvc-jumpositif_$counter',
    error: '.help-block',
    // validate:  function (attribute, value, messages, deferred) {
    //     yii.validation.required(value, messages, {message: 'Pencapaian Tidak Boleh Dibiarkan Kosong'});
    // }
});

", yii\web\View::POS_END);
