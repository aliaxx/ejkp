<?php

use backend\modules\makanan\models\Transkolam;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var View $this
 * @var Transkolam $modelkolam
 * @var ActiveForm $form
 */

$this->title = 'Parameter Air Kolam';
$this->params['breadcrumbs'] = [
'Mutu Makanan',
['label' => 'Pemeriksaan Kolam', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/makanan/kolam/kolam', 'nosiri' => $model->NOSIRI]],
];

?>

<div>
<div style="margin-top:61px;"></div>
<?= $this->render('_tab', ['model' => $model]) ?>
<br>

    <?php $form = ActiveForm::begin(['method' => 'post', 'options'=> ['target' => '_blank']]); ?>
        <?= \codetitan\widgets\ActionBar::widget([
            'target' => 'primary-grid',
            'permissions' => ['new' => Yii::$app->access->can('PKK-write')],
        ]) ?> 
        <div class="action-buttons">
            <?= $this->render('_print') ?>
        </div>
    <?php ActiveForm::end(); ?>

    <?php $form = ActiveForm::begin([
        'id' => 'transkolam-form', //ID TU REFER NAMA MODEL-TENGOK DEKAT F12 NAK CONFIRM (alia-080922)
    ]); ?>

    <div style="margin-top:20px;"></div>

    <?= \codetitan\widgets\ActionBar::widget([
        'permissions' => ['save2close' => Yii::$app->access->can('PKK-write')],
    ]) ?>

    <!-- table title -->
    <br>
    <h4>Parameter Air Kolam * :  <button id="btnAddItem" type="button" class="btn btn-success waves-effect"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah') ?></button></h4>
    <table id="kolamTable" class="table table-condensed table-bordered">
        <thead>
            <tr>
                <tr>
                    <th width=18%><?= Yii::t('app', 'Parameter') ?></th>
                    <th width=10%><?= Yii::t('app', 'Nilai Piawaian') ?></th>
                    <th width=10%><?= Yii::t('app', 'Unit') ?></th>
                    <th width=12%><?= Yii::t('app', 'Nilai Bacaan 1') ?></th>
                    <th width=12%><?= Yii::t('app', 'Nilai Bacaan 2') ?></th>
                    <th width=12%><?= Yii::t('app', 'Nilai Bacaan 3') ?></th>
                    <th width=12%><?= Yii::t('app', 'Nilai Bacaan 4') ?></th>
                    <th width=5%><?= Yii::t('app', 'Tindakan') ?></th>
                </tr>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-9.5 action-buttons">
        <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
            'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1, 
            'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
        ]) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>


<?php
$this->registerCss("
#kolamTable > thead > tr > th {text-align:center}
#kolamTable > tbody > tr > td {text-align:center}
#kolamTable > tfoot > tr > td {font-size:16px;font-weight:bold;background-color:#CCC}
#kolamTable > tfoot > tr > td:first-child {text-align:right}
#kolamTable > tfoot > tr > td:nth-child(2) {text-align:center}
.form-horizontal .form-group {
    margin-right: 0;
    margin-left: 0;
}
");

$this->registerJs("
var itemCounter = 1;

function getItemForm(initialize)
{
    // alert(initialize);
    let urlItemForm = '" . Url::to(['/makanan/kolam/get-item-form']) . "';
    $.get(urlItemForm + '?counter=' + itemCounter + '&initialize=' + initialize, function (data) {
        $('#kolamTable tbody').append(data);
    });
}

$(document).ready(function () {
    
    $('#btnAddItem').click(function () {
        getItemForm(0);
        itemCounter++;
    });
   
    $('#transkolam-form').on('click', 'button[id^=\"btnRemoveRow_\"]', function () {
        let rowToRemove = this.id.split('_').pop();
        $('#itemRow_' + rowToRemove).remove();
    });
    
});
 
", View::POS_END);

// var_dump($modelkolam->airkolam);
// exit();
if (!$modelkolam->airkolam) {
  
    $this->registerJs("
    $(document).ready(function () {
        getItemForm(0);
        itemCounter++;
    });
    ", View::POS_END);
} else {
    $this->registerJs("
    $(document).ready(function () {
        itemCounter = " . (count($modelkolam->airkolam) + 1) . ";
        getItemForm('" . $modelkolam->NOSIRI . "');
        
    });
    ", View::POS_END);
}

