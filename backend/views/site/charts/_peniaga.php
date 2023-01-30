<?php

use miloschuman\highcharts\Highcharts;
use backend\modules\lawatanmain\models\LawatanMain;
/* @var $this yii\web\View */

$tahun = date('Y');
$chart['lawatan'] = [];

$models = LawatanMain::find()->select(['IDMODULE', 'TOTAL' => 'count(*)'])
    ->where(['STATUS' => 1])
    ->andWhere(['between', 'TRKHMULA',$tahun.'/01/01 00:00:00', $tahun.'/12/31 23:59:59'])
    ->groupBy('IDMODULE')->all();
    
foreach ($models as $model) {
    $chart['lawatan'][] = ['name' => $model->idmodule->PRGN, 'y' => (int) $model->TOTAL];
}

?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= Highcharts::widget([
            'options' => [
                'chart' => ['type' => 'pie'],
                'align' => 'top',
                'title' => ['text' => 'Aktiviti Setiap Unit Berdasarkan tahun '.date('Y'),'style' => ['fontSize' => '16px']],
                //'subtitle' => ['text' => 'Cawangan '.$cawangan->nama_cawangan],
                'plotOptions' => [
                    'pie' => [
                        'dataLabels' => [
                            'enabled' => true,
                            'format' => '{point.name}: {point.percentage:.1f}%',
                        ],
                        //'showInLegend' => true,
                        'showInLegend' => false, //zihan 20210318 : to avoid overlapping on datalabels
                    ],
                ],
                'series' => [
                    ['name' => 'Jumlah', 'data' => $chart['lawatan']],
                ],
                'legend' => [
                    'align' => 'bottom',
                    //'verticalAlign' => 'middle',
                    //'layout' => 'vertical',
                    'verticalAlign' => 'bottom',
                    'layout' => 'horizontal',   //zihan 20210318 : puan bib request
                    'itemMarginBottom' => 5,
                ],
                'credits' => ['enabled' => false],
            ]
        ]) ?>
    </div>
</div>