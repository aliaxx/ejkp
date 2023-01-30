<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;

use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\parameter\modules\pemeriksaan\models\ParameterHeaderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Carian Kumpulan';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    'Parameter Carian',
    'Carian Kumpulan',
];
?>

<div>
    <?php $form = ActiveForm::begin(); ?>
        <?= \codetitan\widgets\ActionBar::widget([
            'target' => 'primary-grid',
            'permissions' => ['new' => Yii::$app->access->can('param-header-write')],
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
        'ID' => 'primary-grid',
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
            'KODJENIS',
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
                'value' => 'updatedByUser.NAMA',
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
            ],
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

                        return Html::a('<span class="fa fa-toggle-off"></span>', ['delete', 'id' => $model->KODJENIS], $options);
                    },
                    'inactive' => function ($url, $model) {
                        $options = [
                            'title' => 'Tidak Aktif',
                            'aria-label' => 'Tidak Aktif',
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan ditukar menjadi "Tidak Aktif". Teruskan?',
                            'data-method' => 'post',
                        ];

                        return Html::a('<span class="fa fa-toggle-on"></span>', ['delete', 'id' => $model->KODJENIS], $options);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('param-header-read'),
                    'update' => \Yii::$app->access->can('param-header-write'),
                    'active' => function ($model) {
                        return \Yii::$app->access->can('param-header-write') && ($model->STATUS == OptionHandler::STATUS_TIDAK_AKTIF);
                    },
                    'inactive' => function ($model) {
                        return \Yii::$app->access->can('param-header-write') && ($model->STATUS == OptionHandler::STATUS_AKTIF);
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
