<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

use common\utilities\OptionHandler;
use backend\modules\penyelenggaraan\models\Subunit;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Profil Pengguna';
$this->params['breadcrumbs'] = [
    'Kawalan Pengguna',
    'Penyelenggaraan Pengguna',
    'Profil Pengguna'
];

// Extended attributes
//atribut lanjutan
$extender = \codetitan\widgets\GridNav::formatExtender(Yii::$app->controller->route,
    ['CUSTOMERID','SUBUNIT', 'PERANAN', 'DATA_FILTER', 'STATUS', 'PGNDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'],
    ['PERANAN', 'DATA_FILTER', 'STATUS', 'PGNDAFTAR', 'PGNAKHIR','TRKHAKHIR'], true);
$visible = $extender['visible'];

//dropdown search dekat grid table untuk Lokasi Penjaja. 
$source = Subunit::find()->all();
$option['subunit'] = ArrayHelper::map($source, 'ID', 'PRGN');

?>

<div>
    <?php $form = ActiveForm::begin(); ?>
        <?= \codetitan\widgets\ActionBar::widget([
            'target' => 'primary-grid',
            'permissions' => ['new' => Yii::$app->access->can('pengguna-write')],
        ]) ?>
    <div class="action-buttons">
    <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar', ['user/cari'], ['class' => 'btn btn-primary']) ?>
        <?= $this->render('@backend/views/layouts/_print') ?>
    </div>
    <?php ActiveForm::end(); ?>

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
                'attribute' => 'CUSTOMERID',
                'visible' => in_array('CUSTOMERID', $visible),
            ],           
            'NOKP',
            'NAMA',
            // [
            //     'attribute' => 'SUBUNIT',
            //     'value' => 'subunit0.PRGN', //calling direclty from function in models -NOR26082022
            //     // 'value' => function ($model) {//return value using $model -NOR26082022
            //     //     return isset($model->subunit0->PRGN)?$model->subunit0->PRGN:null;                   
            //     // },                
            //     'visible' => in_array('SUBUNIT', $visible),
            // ],     
            [
                'attribute' => 'SUBUNIT',
                'value' => 'subunit0.PRGN',
                'visible' => in_array('SUBUNIT', $visible),
                'filter' => $option['subunit'],     
            ], 
            [
                'attribute' => 'PERANAN',
                'filter' => \common\utilities\OptionHandler::render('PERANAN'),
                'value' => function ($model) {
                    return \common\utilities\OptionHandler::resolve('PERANAN', $model->PERANAN);
                },
                'visible' => in_array('PERANAN', $visible),
            ],
            // [
            //     'attribute' => 'DATA_FILTER',
            //     'filter' => \common\utilities\OptionHandler::render('DATA_FILTER'),
            //     'value' => function ($model) {
            //         return \common\utilities\OptionHandler::resolve('DATA_FILTER', $model->DATA_FILTER);
            //     },
            //     'visible' => in_array('DATA_FILTER', $visible),
            // ],
           
            [
                'attribute' => 'STATUS',
                'filter' => \common\utilities\OptionHandler::render('STATUS'),
                'value' => function ($model) {
                    return \common\utilities\OptionHandler::resolve('STATUS', $model->STATUS);
                },
                'visible' => in_array('STATUS', $visible),
            ],
            [
                'attribute' => 'PGNDAFTAR',
                'value' => 'updatedByUser.NAMA',
                'visible' => in_array('PGNDAFTAR', $visible),
            ],
            [
                'attribute' => 'PGNAKHIR',
                'value' => 'updatedByUser.NAMA',
                'visible' => in_array('PGNAKHIR', $visible),
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
                'visible' => in_array('TRKHAKHIR', $visible),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => "{view} {update} {active}{inactive}",
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'active' => function ($url, $model) {
                        $options = [
                            'title' => 'Aktif',
                            'aria-label' => 'Aktif',
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan ditukar menjadi "Aktif". Teruskan?',
                            'data-method' => 'post',
                        ];

                        return Html::a('<span class="fa fa-toggle-off"></span>', ['delete', 'id' => $model->ID], $options);
                    },
                    'inactive' => function ($url, $model) {
                        $options = [
                            'title' => 'Tidak Aktif',
                            'aria-label' => 'Tidak Aktif',
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan ditukar menjadi "Tidak Aktif". Teruskan?',
                            'data-method' => 'post',
                        ];

                        return Html::a('<span class="fa fa-toggle-on"></span>', ['delete', 'id' => $model->ID], $options);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('pengguna-read'),
                    'update' => \Yii::$app->access->can('pengguna-write'),
                    'active' => function ($model) {
                        return \Yii::$app->access->can('pengguna-write') && ($model->STATUS == OptionHandler::STATUS_TIDAK_AKTIF);
                    },
                    'inactive' => function ($model) {
                        return \Yii::$app->access->can('pengguna-write') && ($model->STATUS == OptionHandler::STATUS_AKTIF);
                    },
                ],
            ],
        ],
    ]); ?>

    <?= \common\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
        'model' => $searchModel,
        'addon' => ['extender' => $extender],
    ]) ?>
</div>
