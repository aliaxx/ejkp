<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
/**
 * @var View $this
 * @var Anugerah $model
 */


?>

<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Carian Tambahan') ?>
            <span class="pull-right"><i id="toggle-opt" class="fa fa-plus" style="cursor:pointer"></i></span>
        </div>
        <div class="panel-body" id="search-body">
            <div class="row">
                <div class="col-md-12">
                    <?php $form = ActiveForm::begin(['method' => 'get']) ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOLESEN') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOSSM') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'NAMAPREMIS') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'NAMASYARIKAT') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOKPPEMOHON') ?>
                        </div>
                        
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right">
                                <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['/premis/anugerah/carian'], [
                                    'class' => 'btn btn-default',
                                ]) ?>
                                <?= Html::submitButton('<i class="fa fa-search"></i> Cari', [
                                    'class' => 'btn btn-primary',
                                ]) ?>
                            </span>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
$this->registerJs("
let searchBody = 1;
function toggleSearchBody(toggle)
{
    if (toggle) {
        searchBody = 0;
        $('#search-body').show(600, function (){
            $('#toggle-opt').toggleClass('fa-plus');
            $('#toggle-opt').toggleClass('fa-minus');
        });
    } else {
        searchBody = 1;
        $('#search-body').hide(600, function (){
            $('#toggle-opt').toggleClass('fa-minus');
            $('#toggle-opt').toggleClass('fa-plus');
        });
    }
}

$(document).ready(function () {
    $('#toggle-opt').click(function () {
        toggleSearchBody(searchBody);
    });
});
");
?>