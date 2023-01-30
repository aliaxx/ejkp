<?php

use backend\modules\peniaga\models\KawalanPerniagaan;
use common\utilities\DateTimeHelper;
use common\models\Pengguna;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\View;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ButtonDropdown;
use kartik\widgets\DatePicker;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\kpp\models\KppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//dropdown search dekat grid table untuk kategori premis. 
// $source = \backend\modules\penyelenggaraan\models\ParamDetail::find()->where(['KODJENIS' => 1])->all();
// $option['kategori'] = \yii\helpers\ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

$this->title = 'Anugerah Premis';
$this->params['breadcrumbs'] =['Penggredan Premis Makanan','Anugerah Premis Bersih'];

// Atribut lanjutan
// $extender = \codetitan\widgets\GridNav::formatExtender(
//     Yii::$app->controller->route,
//     ['NOSIRI','TRKHMULA','TRKHTAMAT','IDZONAM','LONGITUD', 'LATITUD','PEMERIKSA','STATUS', 'PGNDAFTAR','TRKHDAFTAR','PGNAKHIR','TRKHAKHIR',],
//     ['NOSIRI', 'TRKHMULA','TRKHTAMAT','PGNAKHIR', 'TRKHAKHIR'], 
//     true
// );
// $visible = $extender['visible'];

?>

<div>

    <div class="horizontal-divider"></div>

    <?php $form = ActiveForm::begin(); ?>
    <?= \codetitan\widgets\ActionBar::widget([
        'target' => 'primary-grid',
        'permissions' => ['new' => Yii::$app->access->can('APB-write')],
    ]) ?>
    <!-- <div class="action-buttons">
        <?= Html::submitButton('<i class="glyphicon glyphicon-plus-sign"></i> Daftar', [
            'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
            'title' => 'Daftar', 'aria-label' => 'Daftar', 'data-pjax' => 0,
        ]) ?>
    </div> -->
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
            <?= Html::a('Carian Premis', ['carian'], ['class' => 'btn btn-success']) ?>
            <p>
            </span>
        </div>
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
            'NOSIRI',
            'NOLESEN',
            'NOSSM',
            'TAHUN',
            'GRED',
            'CATATAN',
            [
                'attribute' => 'STATUSREKOD',
                'filter' => \common\utilities\OptionHandler::render('STATUS'),
                'value' => function ($model) {
                    return \common\utilities\OptionHandler::resolve('STATUS', $model->STATUS);
                },
                // 'visible' => in_array('STATUS', $visible),
            ],
            [
                'attribute' => 'PGNDAFTAR',
                'value' => 'createdByUser.NAMA', //user will see name instead of id
                // 'visible' => in_array('PGNDAFTAR', $visible),
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHDAFTAR',
                // 'visible' => in_array('TRKHDAFTAR', $visible),
            ],
            [
                'attribute' => 'PGNAKHIR',
                'value' => 'updatedByUser.NAMA', //user will see name instead of id
                // 'visible' => in_array('PGNAKHIR', $visible),
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
                // 'visible' => in_array('TRKHAKHIR', $visible),
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

                        return Html::a('<span class="fa fa-toggle-off"></span>', ['delete', 'id' => $model->NOSIRI], $options);

                    },
                    'inactive' => function ($url, $model) {
                        $options = [
                            'title' => 'Tidak Aktif',
                            'aria-label' => 'Tidak Aktif',
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan ditukar menjadi "Tidak Aktif". Teruskan?',
                            'data-method' => 'post',
                        ];

                        return Html::a('<span class="fa fa-toggle-on"></span>', ['delete', 'id' => $model->NOSIRI], $options);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('APB-read'),
                    'update' => \Yii::$app->access->can('APB-write'),
                    'active' => function ($model) {
                        return \Yii::$app->access->can('APB-write') && ($model->STATUS == OptionHandler::STATUS_TIDAK_AKTIF);
                    },
                    'inactive' => function ($model) {
                        return \Yii::$app->access->can('APB-write') && ($model->STATUS == OptionHandler::STATUS_AKTIF);
                    },
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
        'model' => $searchModel,
        // 'addon' => ['extender' => $extender],
    ]) ?>
</div>
<?php
