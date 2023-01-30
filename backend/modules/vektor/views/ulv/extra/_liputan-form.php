<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
// use kartik\widgets\ActiveForm;
use backend\modules\vektor\models\SasaranUlv;
use backend\modules\vektor\models\SasaranSrtSearch;
use backend\modules\penyelenggaraan\models\ParamDetail;


/**
 * @var View $this
 * @var SasaranUlv $model
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
        <?= $form->field($liputan, 'ID_JENISPREMIS[]')->widget(Select2::className(), [
            'data' => $data['jenis-premis'],
            'options' => [
                'id' => 'sasaranulv-id_jenispremis_' . $counter,
                'placeholder' => '',
                // 'style' => 'height:26px; width:200px',
                'value' => isset($tmpData) ? $tmpData['ID_JENISPREMIS'] : null, //display from table sasaranulv -NOR300922
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false) ?>
    </td>
    <td>
        <!-- lable false untuk tidak display lable dalam dan luar box -NOR28092022 -->
        <?= $form->field($liputan, 'SASARAN1[]')->textInput([
            'type' => 'number', 
            'min' => ($liputan->isNewRecord ? 1 : 0),
            'id' => 'sasaranulv-sasaran1_' . $counter,
            'autocomplete' => 'off',
            // 'style' => 'height:35px; width:200px',
            'value' => isset($tmpData) ? $tmpData['SASARAN1'] : null,
        ])->label(false) ?>
    </td>
    <td>
        <?= $form->field($liputan, 'PENCAPAIAN1[]')->textInput([
            'type' => 'number', 
            'min' => ($liputan->isNewRecord ? 1 : 0),            
            'id' => 'sasaranulv-pencapaian1_' . $counter,
            'autocomplete' => 'off',
            // 'style' => 'height:35px; width:200px',
            'value' => isset($tmpData) ? $tmpData['PENCAPAIAN1'] : null,
        ])->label(false) ?>
    </td>
    <td>
        <?= $form->field($liputan, 'SASARAN2[]')->textInput([
            'type' => 'number', 
            'min' => ($liputan->isNewRecord ? 1 : 0),
            'id' => 'sasaranulv-sasaran2_' . $counter,
            'autocomplete' => 'off',
            // 'style' => 'height:35px; width:200px',
            'value' => isset($tmpData) ? $tmpData['SASARAN2'] : null,
        ])->label(false) ?>
    </td>
    <td>
        <?= $form->field($liputan, 'PENCAPAIAN2[]')->textInput([
            'type' => 'number', 
            'min' => ($liputan->isNewRecord ? 1 : 0),           
            'id' => 'sasaranulv-pencapaian2_' . $counter,
            'autocomplete' => 'off',
            // 'style' => 'height:35px; width:200px',
            'value' => isset($tmpData) ? $tmpData['PENCAPAIAN2'] : null,
        ])->label(false) ?>
    </td>
    <td>
        <?php if ($counter > 1) : ?>
            <button id="btnRemoveRow_<?= $counter ?>" type="button" class="btn btn-danger waves-effect" title="<?= Yii::t('app', 'Click to remove') ?>">
                <i class="fa fa-times"></i>
            </button>
        <?php endif; ?>
    </td>
</tr>
<?php
$this->registerJs("
$('.field-sasaranulv-id_jenispremis').toggleClass('required');
$('#sasaranulv-form').yiiActiveForm('add', {
    id: 'sasaranulv-id_jenispremis_$counter',
    name: 'SasaranUlv[id_jenispremis][]',
    container: '.field-sasaranulv-id_jenispremis_$counter',
    input: '#sasaranulv-id_jenispremis_$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Jenis Premis Tidak Boleh Dibiarkan Kosong'});
    }
});

$('.field-sasaranulv-sasaran1').toggleClass('required');
$('#sasaranulv-form').yiiActiveForm('add', {
    id: 'sasaranulv-sasaran1_$counter',
    name: 'SasaranUlv[SASARAN1][]',
    container: '.field-sasaranulv-sasaran1_$counter',
    input: '#sasaranulv-sasaran1_$counter',
    error: '.help-block',
    // validate:  function (attribute, value, messages, deferred) {
    //     yii.validation.required(value, messages, {message: 'Jumlah Premis Tidak Boleh Dibiarkan Kosong'});
    // }
});


$('.field-sasaranulv-pencapaian1').toggleClass('required');
$('#sasaranulv-form').yiiActiveForm('add', {
    id: 'sasaranulv-pencapaian1_$counter',
    name: 'SasaranUlv[PENCAPAIAN1][]',
    container: '.field-sasaranulv-pencapaian1_$counter',
    input: '#sasaranulv-pencapaian1_$counter',
    error: '.help-block',
    // validate:  function (attribute, value, messages, deferred) {
    //     yii.validation.required(value, messages, {message: 'Pencapaian Tidak Boleh Dibiarkan Kosong'});
    // }
});

$('.field-sasaranulv-sasaran2').toggleClass('required');
$('#sasaranulv-form').yiiActiveForm('add', {
    id: 'sasaranulv-sasaran2_$counter',
    name: 'SasaranUlv[SASARAN2][]',
    container: '.field-sasaranulv-sasaran2_$counter',
    input: '#sasaranulv-sasaran1_$counter',
    error: '.help-block',
    // validate:  function (attribute, value, messages, deferred) {
    //     yii.validation.required(value, messages, {message: 'Jumlah Premis Tidak Boleh Dibiarkan Kosong'});
    // }
});

$('.field-sasaranulv-pencapaian2').toggleClass('required');
$('#sasaranulv-form').yiiActiveForm('add', {
    id: 'sasaranulv-pencapaian2_$counter',
    name: 'SasaranUlv[PENCAPAIAN2][]',
    container: '.field-sasaranulv-pencapaian2_$counter',
    input: '#sasaranulv-pencapaian2_$counter',
    error: '.help-block',
    // validate:  function (attribute, value, messages, deferred) {
    //     yii.validation.required(value, messages, {message: 'Pencapaian Tidak Boleh Dibiarkan Kosong'});
    // }
});

", yii\web\View::POS_END);
