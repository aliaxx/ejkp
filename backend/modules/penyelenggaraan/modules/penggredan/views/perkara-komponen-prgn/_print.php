<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="print-pdf">
<div align="center"><b>Sistem Jabatan Perkhidmatan dan Persekitaran (eJKP)</b>
<br>Majlis Bandaraya Petaling Jaya

<div align="center"><b>Senarai Butir Kesalahan</b></div>
<p></p>
    <?= GridView::widget([
        'id' => 'primary-grid',
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-bordered table-striped table-condensed table-hover table-responsive'],
        'layout' => '{items}',
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn', 
                'headerOptions' => ['class' => 'text-center', 'style' => 'width:25px'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            'kodakta',
            [
                'attribute' => 'kodsalah',
                'value' => 'kesalahan.seksyen',
            ],
            'kodbutir',
            'prgn:ntext',
            [
                'attribute' => 'status',
                'filter' => \common\utilities\OptionHandler::render('status'),
                'value' => function ($model) {
                    return \common\utilities\OptionHandler::resolve('status', $model->status);
                },
            ],
            [
                'attribute' => 'pgnakhir',
                'value' => 'updatedByUser.nama',
            ],
            'trkhakhir:datetime',
        ],
    ]) ?>

    <div><b>Jumlah <?= number_format($dataProvider->totalCount) ?> rekod</b></div>
</div>
