<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="print-pdf">
<div align="center"><b>Sistem Jabatan Perkhidmatan dan Persekitaran (eJKP)</b>
<br>Majlis Bandaraya Petaling Jaya</div>
<div align="center"><b>Senarai Parameter Detail</b></div>
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
            [
                'attribute' => 'KODJENIS',       
                'value' => 'paramHeader.PRGN',
            ],
            'KODDETAIL',
            'PRGN:ntext',
            [
                'attribute' => 'STATUS',
                'filter' => \common\utilities\OptionHandler::render('STATUS'),
                'value' => function ($model) {
                    return \common\utilities\OptionHandler::resolve('STATUS', $model->STATUS);
                },
            ],

            
            [
                'attribute' => 'PGNAKHIR',
                'value' => 'updatedByUser.nama',
            ],
            'TRKHAKHIR:datetime',
        ],
    ]) ?>

    <div><b>Jumlah <?= number_format($dataProvider->totalCount) ?> rekod</b></div>
</div>
