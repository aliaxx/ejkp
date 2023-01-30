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

use backend\modules\premis\models\Transpremis;

/**
 * @var View $this
 * @var transpremis $gredpremis
 * @var int $counter
 * @var array $data
 */

if ($checkall === "false") {
    if ($tmpData['ISEXIST']) {
        $checked = 1;
    }else{
        $checked = null;
    }
}elseif ($checkall === "true"){ //new record for gred_premis
    $checked = 1;
}

$gredpremis->checked = $checked;

$premis = Transpremis::findAll(['NOSIRI' => $tmpData['ISEXIST']]);  
// if()

// var_dump($premis);
// exit;
// $tempdemerit=$tmpData['MARKAH'];
if($premis){
    $tempdemerit = $tmpData['DEMERIT'];
    // $tempdemerit;
}else{
    $tempdemerit=$tmpData['MARKAH'];
}
// $markah=$tmpData['CHKITEM']
// var_dump($tmpData['MARKAH']);
// exit();

?>
<?php $form = ActiveForm::begin(); ?>
<tr id="itemRow_<?= $counter ?>">
    <?php $checkboxTemplate = '<label class="col-sm-6 control-label pull-right"></label>'; ?>
    <td><?= $counter ?>
        <?= $form->field($gredpremis, 'CHKITEM[]')->checkbox([
            //'value' => isset($tmpData) ? $tmpData['CHKITEM']: null,
            'value' => $counter ,
            //'checked' => $tmpData['ISEXIST']? true : false,
            'checked' => $checked ? true : false,
            'id' => 'transpremis-chkitem_' . $counter,
            'label' => $checkboxTemplate,
            'class' => 'checkbox-inline',
            'uncheck' => null, // removes the default hidden input  
        ]) ?>
    </td>
    <td>
        <?= $form->field($gredpremis, 'PERKARAPRGN[]')->textArea([
            'id' => 'transpremis-kodperkara_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:50px; width:250px',
            'readonly' => true,
            'value' => isset($tmpData) ? $tmpData['KODPERKARA'] . '. ' .  $tmpData['PERKARAPRGN'] : null,
            
        ])->label(false) ?>

        <?= $form->field($gredpremis, 'KODPERKARA[]')->textInput([
            'type' => 'hidden',
            'value' => isset($tmpData) ? $tmpData['KODPERKARA']: null,
        ])->label(false) ?>
    </td>

    <td>
        <?= $form->field($gredpremis, 'KOMPONENPRGN[]')->textArea([
            'id' => 'transpremis-kodkomponen_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:62px; width:320',
            'readonly' => true,
            'value' => isset($tmpData) ? $tmpData['KODKOMPONEN']. '. ' .  $tmpData['KOMPONENPRGN'] : null,
            
        ])->label(false) ?>

        <?= $form->field($gredpremis, 'KODKOMPONEN[]')->textInput([
            'type' => 'hidden',
            'value' => isset($tmpData) ? $tmpData['KODKOMPONEN']: null,
        ])->label(false) ?>

    </td> 

    <td>
        <?= $form->field($gredpremis, 'PRGN[]')->textArea([
            'id' => 'transpremis-kodprgn_' . $counter,
            'readonly' => true,
            'autocomplete' => 'off',
            'style' => 'height:65px; width:400',
            'value' => isset($tmpData) ? $tmpData['PRGN'] : null,
        ])->label(false) ?>

         <?= $form->field($gredpremis, 'KODPRGN[]')->textInput([
            'type' => 'hidden',
            'value' => isset($tmpData) ? $tmpData['KODPRGN']: null,
        ])->label(false) ?>

    </td> 

    <td>
        <?= $form->field($gredpremis, 'MARKAH[]')->textInput([
            'id' => 'transpremis-markah_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:30px; width:60px',
            'readonly' => true,
            'value' => isset($tmpData) ? $tmpData['MARKAH'] : null,
        ])->label(false) ?>
    </td> 

    <td>
        <?= $form->field($gredpremis, 'DEMERIT[]')->textInput([
            'id' => 'transpremis-demerit_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:30px; width:60px',
            'type' => 'number', 
            'min' => '0',
            'max' => $tmpData['MARKAH'],
            'value' => isset($tmpData) ? $tempdemerit : null,
        ])->label(false) ?>
    </td> 

    <td>
        <?= $form->field($gredpremis, 'CATATAN[]')->textArea([
            'id' => 'transpremis-catatan' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:60px; width:200px',
            'value' => isset($tmpData) ? $tmpData['CATATAN'] : null,
        ])->label(false) ?>
    </td>
</tr>



<?php
$this->registerJs("

    $('.field-transpremis-CHKITEM').toggleClass('required');
    $('#transpremis-form').yiiActiveForm('add', {
        id: 'transpremis-chkitem_$counter',
        name: 'transpremis[CHKITEM][]',
        container: '.field-transpremis-chkitem_$counter',
        input: '#transpremis-chkitem_$counter',
        error: '.help-block',
    });

    $('.field-transpremis-KODPERKARA').toggleClass('required');
    $('#transpremis-form').yiiActiveForm('add', {
        id: 'transpremis-kodperkara$counter',
        name: 'transpremis[kodperkara][]',
        container: '.field-transpremis-kodperkara$counter',
        input: '#transpremis-kodperkara$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Perkara Tidak Boleh Dibiarkan Kosong'});
        }
    });

    $('.field-transpremis-KODKOMPONEN').toggleClass('required');
    $('#transpremis-form').yiiActiveForm('add', {
        id: 'transpremis-kodkomponen_$counter',
        name: 'transpremis[KODKOMPONEN][]',
        container: '.field-transpremis-kodkomponen_$counter',
        input: '#transpremis-kodkomponen_$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Komponen Tidak Boleh Dibiarkan Kosong'});
        }
    });

    $('.field-transpremis-KODPRGN').toggleClass('required');
    $('#transpremis-form').yiiActiveForm('add', {
        id: 'transpremis-kodprgn_$counter',
        name: 'transpremis[KODPRGN][]',
        container: '.field-transpremis-kodprgn_$counter',
        input: '#transpremis-kodprgn_$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Penerangan Tidak Boleh Dibiarkan Kosong'});
        }
    });

    $('.field-transpremis-MARKAH').toggleClass('required');
    $('#transpremis-form').yiiActiveForm('add', {
        id: 'transpremis-markah_$counter',
        name: 'transpremis[MARKAH][]',
        container: '.field-transpremis-markah_$counter',
        input: '#transpremis-markah_$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Markah Tidak Boleh Dibiarkan Kosong'});
        }
    });

    // $('.field-transpremis-DEMERIT');
    // $('#transpremis-form').yiiActiveForm('add', {
    //     id: 'transpremis-demerit_$counter',
    //     name: 'transpremis[DEMERIT][]',
    //     container: '.field-transpremis-demerit_$counter',
    //     input: '#transpremis-demerit_$counter',
    //     error: '.help-block',
    //     validate:  function (attribute, value, messages, deferred) {
    //         var check = document.getElementById('transpremis-chkitem_' + $counter).checked;
    //         var markah = document.getElementById('transpremis-markah_' + $counter).value;
    //         var demerit = document.getElementById('transpremis-demerit_' + $counter).value;
            
    //         if(check==true){
    //             yii.validation.required(value, messages, {message: 'Demerit Tidak Boleh Dibiarkan Kosong'});
    //         }
    //     }
    // });

    $('.field-transpremis-DEMERIT');
    $('#transpremis-form').yiiActiveForm('add', {
        id: 'transpremis-demerit_$counter',
        name: 'transpremis[DEMERIT][]',
        container: '.field-transpremis-demerit_$counter',
        input: '#transpremis-demerit_$counter',
        error: '.help-block',
        // validate:  function (attribute, value, messages, deferred) {
        //     var check = document.getElementById('transpremis-chkitem_' + $counter).checked;
        //     var markah = document.getElementById('transpremis-markah_' + $counter).value;
        //     var demerit = document.getElementById('transpremis-demerit_' + $counter).value;
            
        //     if(check==true){
        //         yii.validation.required(value, messages, {message: 'Demerit Tidak Boleh Dibiarkan Kosong'});
        //     }
        // }
    });



    // Autofill form KODKOMPONEN & KODPRGN when choose value from dropdown list namaparam.
    $(document).ready(function(){




        // $('#transpremis-kodperkara$counter').change(function() {          
        //     var idparam = $('#transpremis-kodperkara$counter option:selected').val();
        //     $.get('".Url::to(['/premis/penggredan-premis/get-param-details'])."', {idparam: idparam}, function (data){
        //         $('#transpremis-kodkomponen_$counter').val(data.results.KODKOMPONEN);
        //         $('#transpremis-kodprgn_$counter').val(data.results.KODPRGN);
        //      });
        // });
    });
", yii\web\View::POS_END);
