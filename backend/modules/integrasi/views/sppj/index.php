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
    ['NOICMILIK','NODAFTAR','NODAFTAR','KODAKTA','KODSALAH','TRKHKMP','TRFKMP','TRKHBAYAR','KAUNTER'],
    ['NOICMILIK','NODAFTAR','NODAFTAR','KODAKTA','KODSALAH','TRKHKMP' ,'TRFKMP', 'TRKHBAYAR', 'KAUNTER'],
    true
);
$visible = $extender['visible'];

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
            'primaryKey'=>'NOKMP', 
            [
                'attribute' => 'NOKMP',
                'visible' => in_array('NOKMP', $visible),
            ],
            [
                'attribute' => 'NOICMILIK',
                'visible' => in_array('NOICMILIK', $visible),
            ],
            [
                'attribute' => 'NODAFTAR',
                'visible' => in_array('NODAFTAR', $visible),
            ],
            [
                'attribute' => 'KODAKTA',
                'visible' => in_array('KODAKTA', $visible),
            ],
            [
                'attribute' => 'KODSALAH',
                'visible' => in_array('KODSALAH', $visible),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Tindakan',
                'template' => "{view}",
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'text-center'],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('sppj-read'),
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

