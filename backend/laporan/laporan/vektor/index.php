<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\laporan\AsetInventoriSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'] = $breadCrumbs;

$dropdownActionItems = [];
$dropdownActionItems[] = Html::submitButton('Cetak CSV', [
    'id' => 'action_export-csv', 'name' => 'action[export-csv]',
    'class' => 'btn-dropdown btn btn-default', 'value' => 1, 'data-pjax' => 0,
]);
$dropdownActionItems[] = Html::submitButton('Cetak PDF', [
    'id' => 'action_export-csv', 'name' => 'action[export-pdf]',
    'class' => 'btn-dropdown btn btn-default', 'value' => 1, 'data-pjax' => 0,
]);
$dropdownActionItems[] = Html::button('Cetak Dokumen Pilihan', [
    'class' => 'btn-dropdown btn btn-default',
    'onclick' => 'printBulk()',
]);
?>

<div>
    <?= $this->render('_filter', [
        'model' => $searchModel,
    ]) ?>
</div>