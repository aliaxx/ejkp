<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;

use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;

use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PerananSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$filter['tarikhmasa'] = DatePicker::widget([
    'model' => $searchModel,
    'attribute' => 'TARIKHMASA',
    'options' => [
        'id' => 'tarikhmasaid',
        'autocomplete' => 'off',
    ],
    'pluginOptions' => [
        'minuteStep' => 1,
        'autoclose' => true,
        'todayHighlight' => true,
        'format' => 'dd/mm/yyyy',
        'showMeridian' => true,
    ],
]);

$this->title = 'Log Audit';
$this->params['breadcrumbs'] = [
    'Kawalan Pengguna',
    'Log Audit',
];
?>

<br>
<div>
    <?php $output = GridView::widget([
        'id' => 'primary-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-striped table-condensed table-hover table-responsive'],
        'layout' => '{items}',
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn', 
                'headerOptions' => ['class' => 'text-center', 'style' => 'width:25px'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'filter' => $filter['tarikhmasa'],
                'attribute' => 'TARIKHMASA',
                'value' => function ($model) {
                    return DateTimeHelper::convert($model->TARIKHMASA, false, true);
                }
            ],
            [
                'attribute' => 'TINDAKAN',
                'filter' => OptionHandler::render('log-tindakan'),
                'value' => function ($model) {
                    return OptionHandler::resolve('log-tindakan', $model->TINDAKAN);
                }
            ],
            'NAMATABLE',
            'URLMENU',
            [
                'attribute' => 'PENGGUNA',
                'value' => 'user.NAMA',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Tindakan',
                'template' => "{view}",
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'text-center'],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('Auditlog-read'),
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>
</div>
