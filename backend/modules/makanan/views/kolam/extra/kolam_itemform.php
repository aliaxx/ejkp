<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var View $this
 * @var Transkolam $modelkolam
 * @var int $counter
 * @var array $data
 */

$data['IDPARAM'] = ['' => ''];
$source = Yii::$app->db->createCommand("SELECT ID, NAMAPARAM FROM TBPK_BACAANKOLAM WHERE STATUS=1")->queryAll();
$data['IDPARAM'] = ArrayHelper::map($source, 'ID', 'NAMAPARAM',);

?>

<?php $form = ActiveForm::begin(); ?>
<tr id="itemRow_<?= $counter ?>">
    <td>
        <?= $form->field($modelkolam, 'IDPARAM[]')->widget(Select2::classname(), [
            'data' => $data['IDPARAM'],
            'options' => [
                'id' => 'transkolam-idparam_' . $counter,
                'placeholder' => '',
                'value' => isset($tmpData) ? $tmpData['IDPARAM'] : null,
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false)?>
        
    </td>

    <td>
        <?= $form->field($modelkolam, 'NILAIPIAWAI[]')->textInput([
            'id' => 'transkolam-nilaipiawai_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:30px; width:140px',
            'readonly' => true,
            'value' => isset($tmpData) ? $tmpData['NILAIPIAWAI'] : null,
            'pluginOptions' => [
                'allowClear' => true,
                'ajax' => [
                    'url' =>  Url::to(['/makanan/kolam/get-param-details']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {search:params.term, page:params.page, ID: $("#transkolam-idparam_' . $counter . '").val()}; }'),
                    'processResults' => new JsExpression('function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: { more: (params.page * 20) < data.total }
                        };
                    }'),
                ],
            ],
        ])->label(false) ?>
    </td> 

    <td>
        <?= $form->field($modelkolam, 'UNIT[]')->textInput([
            'id' => 'transkolam-unit_' . $counter,
            'readonly' => true,
            'autocomplete' => 'off',
            'style' => 'height:30px; width:140px',
            'value' => isset($tmpData) ? $tmpData['UNIT'] : null,
        ])->label(false) ?>
    </td> 

    <td>
        <?= $form->field($modelkolam, 'NILAI1[]')->textInput([
            'id' => 'transkolam-nilai1_' . $counter,
            'autocomplete' => 'off',
            // 'type' => 'number', 'step' => '0.01',
            'style' => 'height:30px; width:140px',
            'value' => isset($tmpData) ? $tmpData['NILAI1'] : null,
        ])->label(false) ?>
    </td> 

    <td>
        <?= $form->field($modelkolam, 'NILAI2[]')->textInput([
            'id' => 'transkolam-nilai2_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:30px; width:140px',
            'value' => isset($tmpData) ? $tmpData['NILAI2'] : null,
        ])->label(false) ?>
    </td> 

    <td>
        <?= $form->field($modelkolam, 'NILAI3[]')->textInput([
            'id' => 'transkolam-nilai3_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:30px; width:140px',
            'value' => isset($tmpData) ? $tmpData['NILAI3'] : null,
        ])->label(false) ?>
    </td> 

    <td>
        <?= $form->field($modelkolam, 'NILAI4[]')->textInput([
            'id' => 'transkolam-nilai4_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:30px; width:140px',
            'value' => isset($tmpData) ? $tmpData['NILAI4'] : null,
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

    $('.field-transkolam-idparam').toggleClass('required');
    $('#transkolam-form').yiiActiveForm('add', {
        id: 'transkolam-idparam_$counter',
        name: 'Transkolam[IDPARAM][]',
        container: '.field-transkolam-idparam_$counter',
        input: '#transkolam-idparam_$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Parameter Tidak Boleh Dibiarkan Kosong'});
        }
    });

    $('.field-transkolam-nilaipiawai').toggleClass('required');
    $('#transkolam-form').yiiActiveForm('add', {
        id: 'transkolam-nilaipiawai_$counter',
        name: 'Transkolam[NILAIPIAWAI][]',
        container: '.field-transkolam-nilaipiawai_$counter',
        input: '#transkolam-nilaipiawai_$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Nilai Piawai Tidak Boleh Dibiarkan Kosong'});
        }
    });

    $('.field-transkolam-unit').toggleClass('required');
    $('#transkolam-form').yiiActiveForm('add', {
        id: 'transkolam-unit_$counter',
        name: 'Transkolam[UNIT][]',
        container: '.field-transkolam-unit_$counter',
        input: '#transkolam-unit_$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Nilai 1 Tidak Boleh Dibiarkan Kosong'});
        }
    });

    $('.field-transkolam-nilai1').toggleClass('required');
    $('#transkolam-form').yiiActiveForm('add', {
        id: 'transkolam-nilai1_$counter',
        name: 'Transkolam[NILAI1][]',
        container: '.field-transkolam-nilai1_$counter',
        input: '#transkolam-nilai1_$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Nilai 1 Tidak Boleh Dibiarkan Kosong'});
        }
    });

    $('.field-transkolam-nilai2').toggleClass('required');
    $('#transkolam-form').yiiActiveForm('add', {
        id: 'transkolam-nilai2_$counter',
        name: 'Transkolam[NILAI2][]',
        container: '.field-transkolam-nilai2_$counter',
        input: '#transkolam-nilai2_$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Nilai 2 Tidak Boleh Dibiarkan Kosong'});
        }
    });

    // Autofill form nilaipiawai & unit when choose value from dropdown list namaparam.
    $(document).ready(function(){
        $('#transkolam-idparam_$counter').change(function() {          
            var idparam = $('#transkolam-idparam_$counter option:selected').val();
            $.get('".Url::to(['/makanan/kolam/get-param-details'])."', {idparam: idparam}, function (data){
                $('#transkolam-nilaipiawai_$counter').val(data.results.NILAIPIAWAI);
                $('#transkolam-unit_$counter').val(data.results.UNIT);
             });
        });
    });
", yii\web\View::POS_END);