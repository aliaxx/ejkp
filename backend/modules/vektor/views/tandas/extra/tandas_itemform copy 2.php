<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
// use backend\modules\lawatanmain\models\LawatanMain;

/**
 * @var View $this
 * @var transtandas $gredtandas
 * @var int $counter
 * @var array $data
 */


if ($checkall === "false") {
    if ($tmpData['ISEXIST']) {
        $checked = 1;
    }else{
        $checked = null;
    }
}elseif ($checkall === "true"){ //new record for gred_tandas
    $checked = 1;
}

$gredtandas->checked = $checked;



$jenistandas= explode(',', $pts_jenistandas);


// var_dump($model);
// exit;



?>
<?php $form = ActiveForm::begin(); ?>


<tr id="itemRow_<?= $counter ?>">
    <?php $checkboxTemplate = '<label class="col-sm-6 control-label pull-right"></label>'; ?>
        <td><?= $counter ?>
            <?= $form->field($gredtandas, 'CHKITEM[]')->checkbox([
                //'value' => isset($tmpData) ? $tmpData['CHKITEM']: null,
                'value' => $counter ,
                //'checked' => $tmpData['ISEXIST']? true : false,
                'checked' => $checked ? true : false,
                'id' => 'transtandas-chkitem_' . $counter,
                'label' => $checkboxTemplate,
                'class' => 'checkbox-inline',
                'uncheck' => null, // removes the default hidden input  
            ]) ?>
        </td>
        <td>
            <?= $form->field($gredtandas, 'PERKARAPRGN[]')->textArea([
                'id' => 'transtandas-kodperkara_' . $counter,
                'autocomplete' => 'off',
                'style' => 'height:50px; width:250px',
                'readonly' => true,
                'value' => isset($tmpData) ? $tmpData['KODPERKARA'] . '. ' .  $tmpData['PERKARAPRGN'] : null,
            ])->label(false) ?>

            <?= $form->field($gredtandas, 'KODPERKARA[]')->textInput([
                'type' => 'hidden',
                'value' => isset($tmpData) ? $tmpData['KODPERKARA']: null,
            ])->label(false) ?>

            
            <?= $form->field($gredtandas, 'KOMPONENPRGN[]')->textArea([
                'id' => 'transtandas-kodkomponen_' . $counter,
                'autocomplete' => 'off',
                'style' => 'height:62px; width:338px',
                'readonly' => true,
                'type' => 'hidden',
                'value' => isset($tmpData) ? $tmpData['KOMPONENPRGN'] : null,
            ])->hiddenInput()->label(false) ?>

            <?= $form->field($gredtandas, 'KODKOMPONEN[]')->textInput([
                'type' => 'hidden',
                'value' => isset($tmpData) ? $tmpData['KODKOMPONEN']: null,
            ])->label(false) ?>
        </td>

        <td>
            <?= $form->field($gredtandas, 'PRGN[]')->textArea([
                'id' => 'transtandas-kodprgn_' . $counter,
                'readonly' => true,
                'autocomplete' => 'off',
                'style' => 'height:65px; width:300px',
                'value' => isset($tmpData) ? $tmpData['PRGN'] : null,
            ])->label(false) ?>

            <?= $form->field($gredtandas, 'KODPRGN[]')->textInput([
                'type' => 'hidden',
                'value' => isset($tmpData) ? $tmpData['KODPRGN']: null,
            ])->label(false) ?>

        </td> 

        <td>
            <?= $form->field($gredtandas, 'MARKAH[]')->textInput([
                'id' => 'transtandas-markah_' . $counter,
                'autocomplete' => 'off',
                'style' => 'height:30px; width:60px',
                'readonly' => true,
                'value' => isset($tmpData) ? $tmpData['MARKAH'] : null,
            ])->label(false) ?>
        </td> 
    
        <?php for ($i=0; $i < count($jenistandas); $i++): ?>
        <?php 
        
        if ($jenistandas[$i] == 1) {
            $jenistandas1 = "ML";
        }else if($jenistandas[$i] == 2) {
            $jenistandas1 = "MW";
        }else if($jenistandas[$i] == 3) {
            $jenistandas1 = "MO";
        }else if($jenistandas[$i] == 4) {
            $jenistandas1 = "MU";
        }else if($jenistandas[$i] == 5) {
            $jenistandas1 = "MK";
        }else{
            $jenistandas1 = "";
        }
        ?>

        <td>
            <?= $form->field($gredtandas, $jenistandas1 . '[]')->textInput([
                //'id' => 'transtandas-ml_' . $counter,
                'id' => 'transtandas-' .  $jenistandas1 . '_' . $counter,
                'autocomplete' => 'off',
                'style' => 'height:30px; width:60px',
                'readonly' => false,
                'type' => 'number', 
                'min' => '0',
                'max' => $tmpData['MARKAH'],
                'value' => isset($tmpData) ? $tmpData[$jenistandas1] : null,
                // 'value' =>$gredtandas->markahtandas['total'],
            ])->label(false) ?>
        </td> 
        
        <?php endfor; ?> 

        
     
        <td>
            <?= $form->field($gredtandas, 'JUM_MARKAH[]')->textInput([
                'id' => 'transtandas-jum_markah_' . $counter,
                'autocomplete' => 'off',
                'style' => 'height:30px; width:60px',
                'readonly' => true,
                // 'value' => isset($tmpData) ? $tmpData['J'] : null,
                // 'value' => $gredtandas->markahtandas['total'],
                'value' => '',
            ])->label(false) ?>
        </td> 
</tr>
<?php
$this->registerJs("

$('.field-transtandas-CHKITEM').toggleClass('required');
$('#transtandas-form').yiiActiveForm('add', {
    id: 'transtandas-chkitem_$counter',
    name: 'transtandas[CHKITEM][]',
    container: '.field-transtandas-chkitem_$counter',
    input: '#transtandas-chkitem_$counter',
    error: '.help-block',
});

$('.field-transtandas-ID').toggleClass('required');
$('#transtandas-form').yiiActiveForm('add', {
    id: 'transtandas-id$counter',
    name: 'transtandas[id][]',
    container: '.field-transtandas-id$counter',
    input: '#transtandas-id$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Nama Parameter Tidak Boleh Dibiarkan Kosong'});
    }
});
$('.field-transtandas-KODPERKARA').toggleClass('required');
$('#transtandas-form').yiiActiveForm('add', {
    id: 'transtandas-kodperkara$counter',
    name: 'transtandas[kodperkara][]',
    container: '.field-transtandas-kodperkara$counter',
    input: '#transtandas-kodperkara$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Perkara Tidak Boleh Dibiarkan Kosong'});
    }
});

$('.field-transtandas-KODPRGN').toggleClass('required');
$('#transtandas-form').yiiActiveForm('add', {
    id: 'transtandas-kodprgn_$counter',
    name: 'transtandas[KODPRGN][]',
    container: '.field-transtandas-kodprgn_$counter',
    input: '#transtandas-kodprgn_$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Kritera Tidak Boleh Dibiarkan Kosong'});
    }
});

$('.field-transtandas-MARKAH').toggleClass('required');
$('#transtandas-form').yiiActiveForm('add', {
    id: 'transtandas-markah_$counter',
    name: 'transtandas[MARKAH][]',
    container: '.field-transtandas-markah_$counter',
    input: '#transtandas-markah_$counter',
    error: '.help-block',
    validate:  function (attribute, value, messages, deferred) {
        yii.validation.required(value, messages, {message: 'Markah Tidak Boleh Dibiarkan Kosong'});
    }
});

$('.field-transtandas-ML').toggleClass('required');
$('#transtandas-form').yiiActiveForm('add', {
    id: 'transtandas-ml_$counter',
    name: 'transtandas[ML][]',
    container: '.field-transtandas-ml_$counter',
    input: '#transtandas-ml_$counter',
    error: '.help-block',
});

$('.field-transtandas-MW').toggleClass('required');
$('#transtandas-form').yiiActiveForm('add', {
    id: 'transtandas-mw_$counter',
    name: 'transtandas[MW][]',
    container: '.field-transtandas-mw_$counter',
    input: '#transtandas-mw_$counter',
    error: '.help-block',
});

$('.field-transtandas-MO').toggleClass('required');
$('#transtandas-form').yiiActiveForm('add', {
    id: 'transtandas-mo_$counter',
    name: 'transtandas[MO][]',
    container: '.field-transtandas-mo_$counter',
    input: '#transtandas-mo_$counter',
    error: '.help-block',
    
});

$('.field-transtandas-MU').toggleClass('required');
$('#transtandas-form').yiiActiveForm('add', {
    id: 'transtandas-mu_$counter',
    name: 'transtandas[MU][]',
    container: '.field-transtandas-mu_$counter',
    input: '#transtandas-mu_$counter',
    error: '.help-block',
    
});

$('.field-transtandas-MK').toggleClass('required');
$('#transtandas-form').yiiActiveForm('add', {
    id: 'transtandas-mk_$counter',
    name: 'transtandas[MK][]',
    container: '.field-transtandas-mk_$counter',
    input: '#transtandas-mk_$counter',
    error: '.help-block',
    
});

function sumRow(initialize,checkall,jenistandas)
{

    // let urlItemForm = '" . Url::to(['/vektor/tandas/get-jenis']) . "';
    //     $.get(urlItemForm + '?jenistandas=' + jenistandas, function (data) {
    //         $('#tandasTable tbody').append(data);
    // });

    let urlItemForm = '" . Url::to(['/vektor/tandas/get-jenis']) . "';
        $.get(urlItemForm + '?initialize=' + initialize + '&checkall=' + checkall + '&jenistandas=' + jenistandas, function (data) {
            $('#tandasTable tbody').append(data);
    });
    // alert(jenistandas);
}


$(document).ready(function(){
    var jum_markah = $('#transtandas-JUM_MARKAH').val();
    var ml1= 0;
    var mw1= 0;
    var mo1= 0;
    var mk1= 0;

    $('#transtandas-ML_1').change(function(){

        ml1=$('#transtandas-ML_1').val();
    
        $('#transtandas-ML_1').val();

        $('#transtandas-jum_markah_1').val(ml1);
    });

    $('#transtandas-MK_1').change(function(){
    
        mk1=$('#transtandas-MK_1').val();

        $('#transtandas-MK_1').val();

        $('#transtandas-jum_markah_1').val(mk1);
    });


    $('#transtandas-MO_1').change(function(){  
        mo1=$('#transtandas-MO_1').val();
        $('#transtandas-MO_1').val();

        $('#transtandas-jum_markah_1').val(mo1);
        
    });

    sumRow(0, true, '" . $jenistandas1 ."');
    
});
", yii\web\View::POS_END);