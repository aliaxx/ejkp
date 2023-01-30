<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\premis\models\AnugerahSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="anugerah-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'NOLESEN') ?>

    <?= $form->field($model, 'NOSYARIKAT') ?>

    <?= $form->field($model, 'TAHUN') ?>

    <?= $form->field($model, 'GRED') ?>

    <?= $form->field($model, 'CATATAN') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Carian Premis') ?>
            <span class="pull-right"><i id="toggle-opt" class="fa fa-plus" style="cursor:pointer"></i></span>
        </div>
        <div class="panel-body collapse" id="search-body">
            <div class="row">
                <div class="col-md-12">
                    <?php $form = ActiveForm::begin(['method' => 'get']) ?>
                    
                    
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right">
                                <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['/kompaun/pengurusan'], [
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