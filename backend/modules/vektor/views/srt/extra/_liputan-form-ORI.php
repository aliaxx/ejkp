<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Countries */
/* @var $form yii\widgets\ActiveForm */
?>

<?php

$this->registerJs(
   '$("document").ready(function(){ 
		$("#new_liputan").on("pjax:end", function() {
			$.pjax.reload({container:"#liputantranssrt"});  //Reload GridView
		});
    });'
);
?>

<div class="liputantranssrt-form">

<?php yii\widgets\Pjax::begin(['id' => 'new_liputan']) ?>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ]]); ?>

    <?= $form->field($liputan, 'V_TLENGKAPDALAM')->textInput(['maxlength' => 200]) ?>


    <div class="form-group">
        <?= Html::submitButton($liputan->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), 
        ['class' => $liputan->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>
<?php yii\widgets\Pjax::end() ?>
</div>