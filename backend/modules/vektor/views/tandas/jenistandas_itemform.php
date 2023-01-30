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
 * @var lawatanmain $model
 * @var int $counter
 * @var array $data
 */

//  var_dump($tmpData);
// exit;

//if ($counter == 0 ){
    $data['JENISTANDAS'] = ['' => ''];
    $source = Yii::$app->db->createCommand("SELECT KODDETAIL, PRGN FROM TBPARAMETER_DETAIL WHERE KODJENIS=2 AND STATUS=1")->queryAll();
    $data['JENISTANDAS'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN',);

//}
?>

<?php $form = ActiveForm::begin(); ?>
<tr id="itemRow_<?= $counter ?>">
    <td>
        <?= $form->field($model, 'PTS_JENISTANDAS[]')->widget(Select2::classname(), [
            'data' => $data['JENISTANDAS'],
            'options' => [
                'id' => 'lawatanmain-jenistandas' . $counter,
                'placeholder' => '',
                'value' => isset($tmpData) ? $tmpData['JENISTANDAS'] : null, //$tmpdata baca drpada controller $data['JENISTANDAS']
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false)?>
    </td>

    <td>
        <?= $form->field($model, 'BILTANDAS[]')->textInput([
            'id' => 'lawatanmain-biltandas_' . $counter,
            'autocomplete' => 'off',
            'style' => 'height:25px;',
            'value' => isset($tmpData) ? $tmpData['BILTANDAS'] : null,
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

    $('.field-lawatanmain-jenistandas').toggleClass('required');
    $('#lawatanmain-form').yiiActiveForm('add', {
        id: 'lawatanmain-jenistandas_$counter',
        name: 'lawatanMain[PTS_JENISTANDAS][]',
        container: '.field-lawatanmain-jenistandas_$counter',
        input: '#lawatanmain-jenistandas_$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Jenis Tandas Tidak Boleh Dibiarkan Kosong'});
        }
    });

    $('.field-lawatanmain-biltandas').toggleClass('required');
    $('#lawatanmain-form').yiiActiveForm('add', {
        id: 'lawatanmain-biltandas_$counter',
        name: 'lawatanMain[BILTANDAS][]',
        container: '.field-lawatanmain-biltandas_$counter',
        input: '#lawatanmain-biltandas_$counter',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred) {
            yii.validation.required(value, messages, {message: 'Bilangan Tandas Tidak Boleh Dibiarkan Kosong'});
        }
    });

    // $('#lawatanmain-jenistandas').click(function(e) {
    //     text = $(this).text();
    //     $('#lawatanmain-jenistandas').each(function(index, item) {
    //         if($(item).text() == text) {
    //             $(item).addClass('disabled');
    //         }
    //     })
    // });

    // $('#lawatanmain-jenistandas1').click(function(e){
    //     // text = $(this).text();
    //     var opt=this.value ;    
    //     $('#lawatanmain-jenistandas2').each(function(index, item) {
    //         if($(item).text() == text) {
    //             $(item).addClass('disabled');
    //         }
    //     })
    // });

", yii\web\View::POS_END);
