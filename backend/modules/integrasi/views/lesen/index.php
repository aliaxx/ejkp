<?php

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

$this->title = 'Integrasi Lesen';
$this->params['breadcrumbs'] =['Integrasi','Integrasi Lesen'];

// Atribut lanjutan
$extender = \codetitan\widgets\GridNav::formatExtender(
    Yii::$app->controller->route,
    ['NO_AKAUN','ID_PERMOHONAN','NO_KP_PEMOHON','NAMA_PEMOHON','NAMA_SYARIKAT','NO_DFT_SYKT','TARIKH_PERMOHONAN','JENIS_PREMIS',
    'ALAMAT_PREMIS1','ALAMAT_PREMIS2','ALAMAT_PREMIS3','POSKOD','STATUS_PERMOHONAN','TARIKH_BATAL_TANGGUH', 'KUMPULAN_LESEN', 'KETERANGAN_KUMPULAN',
    'KATEGORI_LESEN','KETERANGAN_KATEGORI','JENIS_LESEN', 'AMAUN_LESEN', 'LOKASI_MENJAJA','JENIS_JUALAN','KAWASAN', 'ID_KAWASAN','JENIS_JAJAAN',],
    ['NO_AKAUN', 'ID_PERMOHONAN', 'NO_KP_PEMOHON', 'NAMA_PEMOHON', 'NAMA_SYARIKAT','JENIS_LESEN','AMAUN_LESEN'],
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
   
   <div class="action-buttons">
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
            'primaryKey'=>'NO_AKAUN', 
            // [
            //     'attribute' => 'NO_AKAUN',
            //     'visible' => in_array('NO_AKAUN', $visible),
            // ],
            [
                'attribute' => 'ID_PERMOHONAN',
                'visible' => in_array('ID_PERMOHONAN', $visible),
            ],
            [
                'attribute' => 'NO_KP_PEMOHON',
                'visible' => in_array('NO_KP_PEMOHON', $visible),
            ],
            [
                'attribute' => 'NAMA_PEMOHON',
                'visible' => in_array('NAMA_PEMOHON', $visible),
            ],
            [
                'attribute' => 'NAMA_SYARIKAT',
                'visible' => in_array('NAMA_SYARIKAT', $visible),
            ],
            [
                'attribute' => 'NO_DFT_SYKT',
                'visible' => in_array('NO_DFT_SYKT', $visible),
            ],
            [
                'attribute' => 'TARIKH_PERMOHONAN',
                'visible' => in_array('TARIKH_PERMOHONAN', $visible),
            ],
            [
                'attribute' => 'JENIS_PREMIS',
                'visible' => in_array('JENIS_PREMIS', $visible),
            ],
            [
                'attribute' => 'ALAMAT_PREMIS1',
                'visible' => in_array('ALAMAT_PREMIS1', $visible),
            ],
            [
                'attribute' => 'ALAMAT_PREMIS2',
                'visible' => in_array('ALAMAT_PREMIS2', $visible),
            ],
            [
                'attribute' => 'ALAMAT_PREMIS3',
                'visible' => in_array('ALAMAT_PREMIS3', $visible),
            ],
            [
                'attribute' => 'POSKOD',
                'visible' => in_array('POSKOD', $visible),
            ],
            [
                'attribute' => 'STATUS_PERMOHONAN',
                'filter' => OptionHandler::render('status-lesen'),
                'value' => function ($model) {
                    return OptionHandler::resolve('status-lesen', $model->STATUS_PERMOHONAN);
                },
            ],
            [
                'attribute' => 'TARIKH_BATAL_TANGGUH',
                'visible' => in_array('TARIKH_BATAL_TANGGUH', $visible),
            ],
            [
                'attribute' => 'KUMPULAN_LESEN',
                'visible' => in_array('KUMPULAN_LESEN', $visible),
            ],
            [
                'attribute' => 'KETERANGAN_KUMPULAN',
                'visible' => in_array('KETERANGAN_KUMPULAN', $visible),
            ],
            [
                'attribute' => 'KATEGORI_LESEN',
                'visible' => in_array('KATEGORI_LESEN', $visible),
            ],
            [
                'attribute' => 'KETERANGAN_KATEGORI',
                'visible' => in_array('KETERANGAN_KATEGORI', $visible),
            ],
            [
                'attribute' => 'JENIS_LESEN',
                'value' => 'penjaja.JENIS_LESEN',
                'visible' => in_array('JENIS_LESEN', $visible),
            ],
            [
                'attribute' => 'AMAUN_LESEN',
                'value' => 'penjaja.AMAUN_LESEN',
                'visible' => in_array('AMAUN_LESEN', $visible),
            ],
            [
                'attribute' => 'LOKASI_MENJAJA',
                'value' => 'penjaja.LOKASI_MENJAJA',
                'visible' => in_array('LOKASI_MENJAJA', $visible),
            ],
            [
                'attribute' => 'JENIS_JUALAN',
                'value' => 'penjaja.JENIS_JUALAN',
                'visible' => in_array('JENIS_JUALAN', $visible),
            ],
            [
                'attribute' => 'KAWASAN',
                'value' => 'penjaja.KAWASAN',
                'visible' => in_array('KAWASAN', $visible),
            ],
            [
                'attribute' => 'ID_KAWASAN',
                'value' => 'penjaja.ID_KAWASAN',
                'visible' => in_array('ID_KAWASAN', $visible),
            ],
            [
                'attribute' => 'JENIS_JAJAAN',
                'value' => 'penjaja.JENIS_JAJAAN',
                'visible' => in_array('JENIS_JAJAAN', $visible),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Tindakan',
                'template' => "{view}",
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'text-center'],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('lesen-read'),
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

