<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="print-pdf">
<div align="center"><b>eJKP </b>
<br>Majlis Bandaraya Petaling Jaya
<br>(MBPJ)</div>
<div align="center"><b>Senarai Profil Pengguna</b></div>
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
            'NOKP',
            'NAMA',
            [
                'attribute' => 'SUBUNIT',
            ],
            [
                'attribute' => 'PERANAN',
                'filter' => \common\utilities\OptionHandler::render('PERANAN'),
                'value' => function ($model) {
                    return \common\utilities\OptionHandler::resolve('PERANAN', $model->PERANAN);
                },
            ],
            [
                'attribute' => 'DATA_FILTER',
                'filter' => \common\utilities\OptionHandler::render('DATA_FILTER'),
                'value' => function ($model) {
                    return \common\utilities\OptionHandler::resolve('DATA_FILTER', $model->DATA_FILTER);
                },
            ],
            [
                'attribute' => 'STATUS',
                'filter' => \common\utilities\OptionHandler::render('STATUS'),
                'value' => function ($model) {
                    return \common\utilities\OptionHandler::resolve('STATUS', $model->STATUS);
                },
            ],
            [
                'attribute' => 'PGNAKHIR',
                'value' => 'updatedByUser.NAMA',
            ],
            'TRKHAKHIR:datetime',
        ],
    ]) ?>

    <div><b>Jumlah <?= number_format($dataProvider->totalCount) ?> rekod</b></div>
</div>
