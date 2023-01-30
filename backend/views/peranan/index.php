<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;

use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PerananSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Peranan';
$this->params['breadcrumbs'] = [
    'Kawalan Pengguna',
    'Penyelenggaraan Pengguna',
    'Peranan Pengguna',
];
?>

<div>
    <?php $form = ActiveForm::begin(); ?>
        <?= \codetitan\widgets\ActionBar::widget([
            'target' => 'primary-grid',
            'permissions' => ['new' => Yii::$app->access->can('peranan-write')],
        ]) ?>
    <div class="action-buttons">
        <?= Html::submitButton('<i class="glyphicon glyphicon-plus-sign"></i> Daftar', [
            'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
            'title' => 'Daftar', 'aria-label' => 'Daftar', 'data-pjax' => 0,
        ]) ?>

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
                'attribute' => 'NAMAPERANAN',
                'label' => 'Nama Peranan',
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
            [
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
            ],
            [  //view peranan akses
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Capaian',
                'template' => "{access}",
                'headerOptions' => ['style' => 'width:30px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'access' => function ($url, $model) {
                        $options = [
                            'title' => 'Senarai Capaian',
                            'aria-label' => 'Akses',
                            'data-pjax' => '0',
                        ];

                        return Html::a('<span class="fa fa-th-list"></span>', ['access', 'id' => $model->IDPERANAN], $options);
                    },
                ],
                'visibleButtons' => [
                    'access' => function ($model) {
                        return \Yii::$app->access->can('peranan-write') && ($model->IDPERANAN != 1);
                    },
                ],
            ],

            //Delete button. This ejkp using toggle button to activate/unactivate the data.
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Tindakan',
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

                        return Html::a('<span class="fa fa-toggle-off"></span>', ['delete', 'id' => $model->IDPERANAN], $options);
                    },
                    'inactive' => function ($url, $model) {
                        $options = [
                            'title' => 'Tidak Aktif',
                            'aria-label' => 'Tidak Aktif',
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan ditukar menjadi "Tidak Aktif". Teruskan?',
                            'data-method' => 'post',
                        ];

                        return Html::a('<span class="fa fa-toggle-on"></span>', ['delete', 'id' => $model->IDPERANAN], $options);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('peranan-read'),
                    'update' => function ($model) {
                        return \Yii::$app->access->can('peranan-write') && ($model->IDPERANAN != 1);
                    },
                    'active' => function ($model) {
                        if ($model->IDPERANAN == 1) return false;
                        return \Yii::$app->access->can('peranan-write') && ($model->STATUS == OptionHandler::STATUS_TIDAK_AKTIF);
                    },
                    'inactive' => function ($model) {
                        if ($model->IDPERANAN == 1) return false;
                        return \Yii::$app->access->can('peranan-write') && ($model->STATUS == OptionHandler::STATUS_AKTIF);
                    },
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>
</div>
