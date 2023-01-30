<?php

use common\utilities\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;

use common\utilities\OptionHandler;
use kartik\widgets\DatePicker;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\bootstrap\ButtonDropdown;
use yii\web\View;
use backend\modules\integrasi\models\LesenMaster;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\kpp\models\KppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Integrasi Sewa';
$this->params['breadcrumbs'][] = 'Integrasi Sewa';

$this->registerCss("
.top-scroll {position:fixed;top:0;right:0;z-index:3;}
");

// Atribut lanjutan
$extender = \codetitan\widgets\GridNav::formatExtender(
    Yii::$app->controller->route,
    ['ACCOUNT_NUMBER','LICENSE_NUMBER','NAME','ADDRESS_1','ADDRESS_2','ADDRESS_3','ADDRESS_POSTCODE','LOT_NO','LOCATION_ID','LOCATION_NAME',
    'RENT_CATEGORY' ,'SALES_TYPE' ,'ASSET_ADDRESS_1' ,'ASSET_ADDRESS_2','ASSET_ADDRESS_3','ASSET_ADDRESS_POSTCODE','ASSET_ADDRESS_LAT','ASSET_ADDRESS_LONG',
    'RENT_AMOUNT' ,'OUTSTANDING_RENT_AMOUNT'],
    ['ACCOUNT_NUMBER','LICENSE_NUMBER','NAME','LOT_NO','LOCATION_NAME','RENT_AMOUNT' ,'OUTSTANDING_RENT_AMOUNT'],
    true
);
$visible = $extender['visible'];
?>

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
                'class' => 'yii\grid\ActionColumn',
                'template' => "{select}",
                'headerOptions' => ['style' => 'width:30px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'select' => function ($url, $model) {
                        $options = [
                            'title' => 'Pilih',
                            'aria-label' => 'Pilih',
                            'data-pjax' => '0',
                            'onclick' => "parent.lookup.select('" . Yii::$app->request->get('target') . "', '" . $model->ACCOUNT_NUMBER . "', '" . $model->ACCOUNT_NUMBER . "')",
                        ];
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', 'javascript:void(0)', $options);
                    },
                ],
            ],
            'primaryKey'=>'ACCOUNT_NUMBER', 
            [
                'attribute' => 'LICENSE_NUMBER',
                'visible' => in_array('LICENSE_NUMBER', $visible),
            ],
            [
                'attribute' => 'NAME',
                'visible' => in_array('NAME', $visible),
            ],
            [
                'attribute' => 'LOT_NO',
                'visible' => in_array('LOT_NO', $visible),
            ],
            [
                'attribute' => 'LOCATION_ID',
                'visible' => in_array('LOCATION_ID', $visible),
            ],
            
            [
                'attribute' => 'LOCATION_NAME',
                'visible' => in_array('LOCATION_NAME', $visible),
            ],
            [
                'attribute' => 'RENT_CATEGORY',
                'visible' => in_array('RENT_CATEGORY', $visible),
            ],
            [
                'attribute' => 'SALES_TYPE',
                'visible' => in_array('SALES_TYPE', $visible),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Tindakan',
                'template' => "{view}",
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'text-center'],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('sewa-read'),
                ],
            ],   
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
        'model' => $searchModel,
        'addon' => ['extender' => $extender],
    ]) ?>
</div>


<?php
$this->registerCss("
.table .select2-container--krajee .select2-selection--single {height: 26px;padding: 4px 24px 6px 12px !important}
.table .select2-container--krajee .select2-selection--single .select2-selection__arrow {height: 25px}
.table .select2-selection__clear {padding:0px}
");

$this->registerJs("
$(document).ready(function () {
    $('#scroll-right').click(function() {
        event.preventDefault();
        $('.gridnav-container').animate({
          scrollLeft: '+=200px'
        }, 'fast');
    });

    $('#scroll-left').click(function() {
        event.preventDefault();
        $('.gridnav-container').animate({
          scrollLeft: '-=200px'
        }, 'fast');
    });
});

", View::POS_END);
