<?php

// use backend\modules\makanan\models\Transkolam;
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

?>
<div>

    <?= \codetitan\widgets\ActionBar::widget([
        'permissions' => ['save2close' => Yii::$app->access->can('PTS-write')],
    ]) ?>

    <!-- table title -->
    <h4>Jenis Tandas* :  <button id="btnAddItem" type="button" class="btn btn-success waves-effect"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Tambah') ?></button></h4>
    <table id="tandasTable" class="table table-condensed table-bordered">
        <thead>
            <tr>
                <tr>
                    <th width=40%><?= Yii::t('app', 'Jenis Tandas') ?></th>
                    <th width=40%><?= Yii::t('app', 'Bilangan Tandas') ?></th>
                    <th width=20%><?= Yii::t('app', 'Tindakan') ?></th>
                </tr>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<?php
$this->registerCss("
#tandasTable > thead > tr > th {text-align:center}
#tandasTable > tbody > tr > td {text-align:center}
#tandasTable > tfoot > tr > td {font-size:16px;font-weight:bold;background-color:#CCC}
#tandasTable > tfoot > tr > td:first-child {text-align:right}
#tandasTable > tfoot > tr > td:nth-child(2) {text-align:center}
.form-horizontal .form-group {
    margin-right: 0;
    margin-left: 0;
}
");

$this->registerJs("
var itemCounter = 1;

function getItemForm(initialize)
{
    let urlItemForm = '" . Url::to(['/vektor/tandas/get-jenis-tandas']) . "';
    $.get(urlItemForm + '?counter=' + itemCounter + '&initialize=' + initialize, function (data) {
        $('#tandasTable tbody').append(data);
    });
}

$(document).ready(function () {
    $('#btnAddItem').click(function () {
        getItemForm(0);
        itemCounter++;
    });

    $('#lawatanmain-form').on('click', 'button[id^=\"btnRemoveRow_\"]', function () {
        let rowToRemove = this.id.split('_').pop();
        $('#itemRow_' + rowToRemove).remove();
    });
});
", View::POS_END);

if (!$model->countertandas) {
  
    $this->registerJs("
    $(document).ready(function () {
        getItemForm(0);
        itemCounter++;
    });
    ", View::POS_END);
} else {
    $this->registerJs("
    $(document).ready(function () {
        itemCounter = " . (count($model->countertandas) + 1) . ";
        getItemForm('" . $model->NOSIRI . "');
        
    });
    ", View::POS_END);
}

