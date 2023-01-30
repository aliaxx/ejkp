<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;

use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\penyelenggaraan\modules\tandas\lokasi\models\LokasiTandasvektorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lokasi Tandas';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    'Parameter Kategori Tandas',
    $this->title
];

?>
<div class="lokasi-tandasvektor-index">

    <?php $form = ActiveForm::begin(); ?>
        <div>
            <?= \codetitan\widgets\ActionBar::widget([
                'target' => 'primary-grid',
                'permissions' => ['new' => Yii::$app->access->can('lokasi-tandas-write')],
                ]) 
            ?>
        </div>

        <div class="action-buttons">
            <?= Html::submitButton('<i class="glyphicon glyphicon-plus-sign"></i> Daftar', [
                'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
                'title' => 'Daftar', 'aria-label' => 'Daftar', 'data-pjax' => 0,
            ])       
            ?>
        </div>
    <?php ActiveForm::end(); ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php $output = GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'primary-grid',
        'tableOptions' => ['class' => 'table table-bordered table-striped table-condensed table-hover table-responsive'],
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'ID',
            [
                'attribute' => 'ID_KATEGORITANDAS',
                'value' => 'kategori.PRGN',
            ],
            'PRGN',
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
            [
                'class' => 'yii\grid\ActionColumn',            
                'header' => 'Tindakan',            
                'template' => "{view} {update} {active}{inactive}",            
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

                        return Html::a('<span class="fa fa-toggle-off"></span>', ['delete', 'ID' => $model->ID], $options);                
                    },
                    'inactive' => function ($url, $model) {
                        $options = [
                            'title' => 'Tidak Aktif',
                            'aria-label' => 'Tidak Aktif',
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan ditukar menjadi "Tidak Aktif". Teruskan?',
                            'data-method' => 'post',
                        ];

                        return Html::a('<span class="fa fa-toggle-on"></span>', ['delete', 'ID' => $model->ID], $options);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('lokasi-tandas-read'),
                    'update' => \Yii::$app->access->can('lokasi-tandas-write'),
                    'active' => function ($model) {
                        return \Yii::$app->access->can('lokasi-tandas-write') && ($model->STATUS == OptionHandler::STATUS_TIDAK_AKTIF);
                    },
                    'inactive' => function ($model) {
                        return \Yii::$app->access->can('lokasi-tandas-write') && ($model->STATUS == OptionHandler::STATUS_AKTIF);
                    },
                ],
            ],       
        ],
    ]); 
    ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output, //define the grid display in page
    ]) ?>

</div>