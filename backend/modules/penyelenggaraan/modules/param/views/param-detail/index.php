<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;

use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\parameter\modules\pemeriksaan\models\AsasTindakanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Carian Terperinci';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    'Parameter Carian',
    'Carian Terperinci',
];

//dropdown search dekat grid table untuk kodjenis. 
$source = \backend\modules\penyelenggaraan\models\ParamHeader::find()->where(['STATUS' => 1])->orderBy(['PRGN' => SORT_ASC])->all();
$option['KODJENIS'] = \yii\helpers\ArrayHelper::map($source, 'KODJENIS', 'PRGN');


?>

<div>
    <!-- button daftar jadi ia akan jadi write sebab nak create data baru -->
    <?php $form = ActiveForm::begin(); ?>
        <?= \codetitan\widgets\ActionBar::widget([
            'target' => 'primary-grid',
            'permissions' => ['new' => Yii::$app->access->can('param-detail-write')],
        ]) ?>
    <div class="action-buttons">
        <?= Html::submitButton('<i class="glyphicon glyphicon-plus-sign"></i> Daftar', [
            'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
            'title' => 'Daftar', 'aria-label' => 'Daftar', 'data-pjax' => 0,
        ]) ?>

        <?= $this->render('@backend/views/layouts/_print') ?>
    </div>
    <?php ActiveForm::end(); ?>

    <!-- Display data Param Detail & Param Header -->
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
                'attribute' => 'KODJENIS',       
                'value' => 'paramHeader.PRGN', //Display nama jenis instead of Kod Jenis 
                'filter' => $option['KODJENIS'], //dropdown search untuk display data bila pilih kod jenis yang didaftarkan di ParamHeader. Nurul 250722
            ],
            'KODDETAIL',//attribute that declare at model
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
                'value' => 'updatedByUser.NAMA', //user will see name instead of id
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

                        return Html::a('<span class="fa fa-toggle-off"></span>', ['delete', 'KODJENIS' => $model->KODJENIS, 'KODDETAIL' => $model->KODDETAIL], $options);
                    },
                    'inactive' => function ($url, $model) {
                        $options = [
                            'title' => 'Tidak Aktif',
                            'aria-label' => 'Tidak Aktif',
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan ditukar menjadi "Tidak Aktif". Teruskan?',
                            'data-method' => 'post',
                        ];

                        return Html::a('<span class="fa fa-toggle-on"></span>', ['delete', 'KODJENIS' => $model->KODJENIS, 'KODDETAIL' => $model->KODDETAIL], $options);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('param-detail-read'),
                    'update' => \Yii::$app->access->can('param-detail-write'),
                    'active' => function ($model) {
                        return \Yii::$app->access->can('param-detail-write') && ($model->STATUS == OptionHandler::STATUS_TIDAK_AKTIF);
                    },
                    'inactive' => function ($model) {
                        return \Yii::$app->access->can('param-detail-write') && ($model->STATUS == OptionHandler::STATUS_AKTIF);
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
