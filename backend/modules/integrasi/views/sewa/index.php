<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DatePicker;
use yii\bootstrap\ButtonDropdown;

use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;

use common\models\Pengguna;
use backend\modules\integrasi\models\Sewa;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\kpp\models\KppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Integrasi Sewa';
$this->params['breadcrumbs'] =['Integrasi','Integrasi Sewa'];

// Atribut lanjutan
$extender = \codetitan\widgets\GridNav::formatExtender(
    Yii::$app->controller->route,
    ['ACCOUNT_NUMBER','LICENSE_NUMBER','NAME','ADDRESS_1','ADDRESS_2','ADDRESS_3','ADDRESS_POSTCODE','LOT_NO','LOCATION_NAME',
    'RENT_CATEGORY' ,'SALES_TYPE' ,'ASSET_ADDRESS_1' ,'ASSET_ADDRESS_2','ASSET_ADDRESS_3','ASSET_ADDRESS_POSTCODE','ASSET_ADDRESS_LAT','ASSET_ADDRESS_LONG',
    'RENT_AMOUNT' ,'OUTSTANDING_RENT_AMOUNT'],
    ['ACCOUNT_NUMBER','LICENSE_NUMBER','NAME','LOT_NO','LOCATION_NAME','RENT_AMOUNT' ,'OUTSTANDING_RENT_AMOUNT'],
    true
);
$visible = $extender['visible'];

// $source = Sewa::find()->all();
// $option['lokasi'] = ArrayHelper::map($source, 'LOCATION_ID', 'LOCATION_NAME');

// $source = Sewa::find()->all();
// $option['kategori'] = ArrayHelper::map($source, 'LOCATION_ID', 'RENT_CATEGORY');

?>

<div>
    <div class="horizontal-divider"></div>

    <?php $form = ActiveForm::begin(); ?>
    <?= \codetitan\widgets\ActionBar::widget([
        'target' => 'primary-grid',
       // 'permissions' => ['new' => Yii::$app->access->can('tandas-write')],
    ]) ?>
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
                'attribute' => 'ADDRESS_1',
                'visible' => in_array('ADDRESS_1', $visible),
            ],
            [
                'attribute' => 'ADDRESS_2',
                'visible' => in_array('ADDRESS_2', $visible),
            ],
            [
                'attribute' => 'ADDRESS_3',
                'visible' => in_array('ADDRESS_3', $visible),
            ],
            [
                'attribute' => 'ADDRESS_POSTCODE',
                'visible' => in_array('ADDRESS_POSTCODE', $visible),
            ],
            [
                'attribute' => 'LOCATION_NAME',
                'visible' => in_array('LOCATION_NAME', $visible),
                // 'filter' => $option['lokasi'],
            ],
            [
                'attribute' => 'RENT_CATEGORY',
                'visible' => in_array('RENT_CATEGORY', $visible),
                // 'filter' => $option['kategori'],
            ],
            [
                'attribute' => 'ASSET_ADDRESS_1',
                'visible' => in_array('ASSET_ADDRESS_1', $visible),
            ],
            [
                'attribute' => 'ASSET_ADDRESS_2',
                'visible' => in_array('ASSET_ADDRESS_2', $visible),
            ],
            [
                'attribute' => 'ASSET_ADDRESS_3',
                'visible' => in_array('ASSET_ADDRESS_3', $visible),
            ],
            [
                'attribute' => 'ASSET_ADDRESS_POSTCODE',
                'visible' => in_array('ASSET_ADDRESS_POSTCODE', $visible),
            ],
            [
                'attribute' => 'SALES_TYPE',
                'visible' => in_array('SALES_TYPE', $visible),
            ],
            [
                'attribute' => 'ASSET_ADDRESS_LAT',
                'visible' => in_array('ASSET_ADDRESS_LAT', $visible),
            ],
            [
                'attribute' => 'ASSET_ADDRESS_LONG',
                'visible' => in_array('ASSET_ADDRESS_LONG', $visible),
            ],
            [
                'attribute' => 'RENT_AMOUNT',
                'visible' => in_array('RENT_AMOUNT', $visible),
            ],
            [
                'attribute' => 'OUTSTANDING_RENT_AMOUNT',
                'visible' => in_array('OUTSTANDING_RENT_AMOUNT', $visible),
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
        'addon' => ['extender' => $extender], //called for atribut lanjutan
    ]) ?>
</div>

