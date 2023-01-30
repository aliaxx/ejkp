<?php

use common\utilities\DateTimeHelper;

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

use common\models\Pengguna;
use backend\modules\integrasi\models\Sewa;
use backend\modules\lawatanmain\models\ZonAm;
use backend\modules\penyelenggaraan\models\ParamDetail;


//dropdown search dekat grid table untuk kategori premis. 
// $source = \backend\modules\penyelenggaraan\models\ParamDetail::find()->where(['KODJENIS' => 1])->all();
// $option['kategori'] = \yii\helpers\ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

$this->title = $title;
$this->params['breadcrumbs'] = $breadCrumbs;

//dropdown search dekat grid table untuk Tujuan. 
$source = ParamDetail::find()->where(['KODJENIS' => 22])->orderBy(['PRGN' => SORT_ASC])->all();
$option['tujuan'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

//dropdown search dekat grid table untuk Lokasi Penjaja. 
$source = Sewa::find()->all();
$option['penjaja'] = ArrayHelper::map($source, 'LOCATION_ID', 'LOCATION_NAME');

//dropdown search dekat grid table untuk Lokasi Ahli Simpanan. 
// $source = ZonAm::find()->all();
// $option['ahlimajlis'] = ArrayHelper::map($source, 'NOMBOR_ZON', 'NAMA');


//dropdown search dekat grid table untuk Jenis Persampelan for SMM. 
$source = ParamDetail::find()->where(['KODJENIS' => 6])->orderBy(['PRGN' => SORT_ASC])->all();
$option['persampelan'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

//dropdown search dekat grid table untuk Tempat Simpanan for SDR. 
$source = ParamDetail::find()->where(['KODJENIS' => 24])->orderBy(['PRGN' => SORT_ASC])->all();
$option['tempatsimpanan'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

//Get data jenis semburan. 
$source = ParamDetail::find()->where(['KODJENIS' => 7])->orderBy(['PRGN' => SORT_ASC])->all();
$option['jenissemburan'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

//allow permission action button to send to controller.
$perRead = $idModule . "-read"; 
$perWrite = $idModule . "-write";
// var_dump($perWrite);
// exit;

$filter['TRKHMULA'] = DatePicker::widget([
    'model' => $searchModel,
    'attribute' => 'TRKHMULA',
    'options' => [
        'id' => 'trkhmulagrid',
        'autocomplete' => 'off',
    ],
    'pluginOptions' => [
        'minuteStep' => 1,
        'autoclose' => true,
        'todayHighlight' => true,
        'format' => 'dd/mm/yyyy',
        'showMeridian' => true,
    ],
]);

$filter['TRKHTAMAT'] = DatePicker::widget([
    'model' => $searchModel,
    'attribute' => 'TRKHTAMAT',
    'options' => [
        'id' => 'trkhtamatgrid',
        'autocomplete' => 'off',
    ],
    'pluginOptions' => [
        'minuteStep' => 1,
        'autoclose' => true,
        'todayHighlight' => true,
        'format' => 'dd/mm/yyyy',
        'showMeridian' => true,
    ],
]);

// Atribut lanjutan
$extender = \codetitan\widgets\GridNav::formatExtender(
    Yii::$app->controller->route,
    ['TRKHMULA','TRKHTAMAT', 'NOSIRI', 'JENISPREMIS', 'ID_TUJUAN', 'PRGNLOKASI_AM','IDLOKASI', 'IDDUN', 'NOADUAN',
    'SDR_ID_STOR','SMM_ID_JENISSAMPEL',
    'PKK_NAMAPENYELIA', 'PKK_NOKPPENYELIA', 'PKK_JENISKOLAM', 'PKK_JENISRAWATAN',
    'V_RUJUKANKES','SRT_ID_SEMBURANSRT','V_NODAFTARKES','V_NOWABAK','V_NOAKTIVITI',
    'NOLESEN', 'NOSSM', 'JENIS_PREMIS', 'NAMASYARIKAT', 'NAMAPREMIS', 'NAMAPEMOHON', 'NOKPPEMOHON',
    'KETUAPASUKAN','NAMAPENERIMA', 'NOKPPENERIMA', 'STATUS', 'PGNDAFTAR', 'PGNAKHIR','TRKHDAFTAR','TRKHAKHIR',],
    ['NOSIRI', 'TRKHMULA', 'NAMAPREMIS','NOLESEN','PRGNLOKASI','ID_TUJUAN','STATUS', 'PGNDAFTAR','TRKHDAFTAR','TRKHAKHIR'], 
    true
);
$visible = $extender['visible'];
?>

<div>

<div class="horizontal-divider"></div>

<?php $form = ActiveForm::begin(['id' => 'lawatan-main-form']); ?>

<?= \codetitan\widgets\ActionBar::widget([
    'target' => 'primary-grid',
    'permissions' => ['save2close' => Yii::$app->access->can($perRead)],
]) ?>

    <div class="action-buttons">
        <?= Html::submitButton('<i class="glyphicon glyphicon-plus-sign"></i> Daftar', [
            'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
            'title' => 'Daftar', 'aria-label' => 'Daftar', 'data-pjax' => 0,
        ]) ?>
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
                'attribute' => 'NOSIRI',
                'visible' => in_array('NOSIRI', $visible),
            ],
            [
                'attribute' => 'TRKHMULA',
                'value' => function ($model) {
                    return date('d-m-Y H:i', strtotime($model->TRKHMULA));
                },
                'filter' => $filter['TRKHMULA'],
                'visible' => in_array('TRKHMULA', $visible),
            ],
            [
                'attribute' => 'TRKHTAMAT',
                'value' => function ($model) {
                    return date('d-m-Y H:i', strtotime($model->TRKHTAMAT));
                },
                'filter' => $filter['TRKHTAMAT'],
                'visible' => in_array('TRKHTAMAT', $visible),
            ],
            [
                'attribute' => 'NAMAPREMIS',
                'value' => 'pemilik0.NAMAPREMIS',
                'visible' => in_array('NAMAPREMIS', $visible),
            ],
            [
                'attribute' => 'NOLESEN',
                'value' => 'pemilik0.NOLESEN',
                'visible' => in_array('NOLESEN', $visible),
            ],
            [
                'attribute' => 'PRGNLOKASI',
                'visible' => in_array('PRGNLOKASI', $visible),
            ],
            [
                'attribute' => 'JENISPREMIS',
                'filter' => backend\modules\makanan\utilities\OptionHandler::render('jenis-premis'),
                'visible' => in_array('JENISPREMIS', $visible),
                'value' => function ($model) {
                    return backend\modules\makanan\utilities\OptionHandler::resolve('jenis-premis', $model->JENISPREMIS);
                },
            ], 
            [
                'attribute' => 'ID_TUJUAN',
                'value' => 'tujuan0.PRGN',
                'visible' => in_array('ID_TUJUAN', $visible),
                'filter' => $option['tujuan'],
            ],
            [
                'attribute' => 'PRGNLOKASI_AM',
                'visible' => in_array('PRGNLOKASI_AM', $visible),
                // 'filter' => $option['ahlimajlis'],
            ],
            [
                'attribute' => 'IDDUN',
                'value' => 'dun0.PRGNDUN',
                'visible' => in_array('IDDUN', $visible),
            ],
            [
                'attribute' => 'NOADUAN',
                'visible' => in_array('NOADUAN', $visible),
            ],
            [
                'attribute' => 'SDR_ID_STOR',
                'value' => 'stor.PRGN',
                'visible' => in_array('SDR_ID_STOR', $visible),
                'filter' => $option['tempatsimpanan'],
            ],
            [
                'attribute' => 'SMM_ID_JENISSAMPEL',
                'value' => 'jenis.PRGN',
                'visible' => in_array('SMM_ID_JENISSAMPEL', $visible),
                'filter' => $option['persampelan'],
            ],
            [
                'attribute' => 'IDLOKASI',
                'value' => 'lokasi0.LOCATION_NAME',
                'visible' => in_array('IDLOKASI', $visible),
                'filter' => $option['penjaja'],
            ],

            //Maklumat Tambahan untuk Kolam(PKK)
            [
                'attribute' => 'PKK_NAMAPENYELIA',
                'visible' => in_array('PKK_NAMAPENYELIA', $visible),
            ],
            [
                'attribute' => 'PKK_NOKPPENYELIA',
                'visible' => in_array('PKK_NOKPPENYELIA', $visible),
            ],
            [
                'attribute' => 'PKK_JENISKOLAM',
                'visible' => in_array('PKK_JENISKOLAM', $visible),
            ],
            [
                'attribute' => 'PKK_JENISRAWATAN',
                'filter' => backend\modules\makanan\utilities\OptionHandler::render('jenisrawatan'),
                'visible' => in_array('PKK_JENISRAWATAN', $visible),
                'value' => function ($model) {
                    return backend\modules\makanan\utilities\OptionHandler::resolve('jenisrawatan', $model->PKK_JENISRAWATAN);
                },
            ],
            //Maklumat Kes/Aktiviti untuk Pencegahan Vektor
            [
                'attribute' => 'V_RUJUKANKES',
                'visible' => in_array('V_RUJUKANKES', $visible),
            ],
            [
                'attribute' => 'SRT_ID_SEMBURANSRT',
                'value' => 'jenisSemburan.PRGN',
                'visible' => in_array('SRT_ID_SEMBURANSRT', $visible),
                'filter' => $option['jenissemburan'],
            ],
            [
                'attribute' => 'V_NODAFTARKES',
                'visible' => in_array('V_NODAFTARKES', $visible),
            ],
            [
                'attribute' => 'V_NOWABAK',
                'visible' => in_array('V_NOWABAK', $visible),
            ],
            [
                'attribute' => 'V_NOAKTIVITI',
                'visible' => in_array('V_NOAKTIVITI', $visible),
            ],
            // Maklumat Premis yang Diperiksa
            [
                'attribute' => 'NOSSM',
                'value' => 'pemilik0.NOSSM',
                'visible' => in_array('NOSSM', $visible),
            ],
            [
                'attribute' => 'JENIS_PREMIS',
                'value' => 'pemilik0.JENIS_PREMIS',
                'visible' => in_array('JENIS_PREMIS', $visible),
            ],
            [
                'attribute' => 'NAMASYARIKAT',
                'value' => 'pemilik0.NAMASYARIKAT',
                'visible' => in_array('NAMASYARIKAT', $visible),
            ],
            
            [
                'attribute' => 'NAMAPEMOHON',       
                'value' => 'pemilik0.NAMAPEMOHON',
                'visible' => in_array('NAMAPEMOHON', $visible),
            ],
            [
                'attribute' => 'NOKPPEMOHON',
                'value' => 'pemilik0.NOKPPEMOHON',
                'visible' => in_array('NOKPPEMOHON', $visible),
            ],
            [
                'attribute' => 'KETUAPASUKAN',
                'value' => 'ketuapasukan0.pengguna0.NAMA',
                'visible' => in_array('KETUAPASUKAN', $visible),
            ],
            [
                'attribute' => 'NAMAPENERIMA',
                'visible' => in_array('NAMAPENERIMA', $visible),
            ],
            [
                'attribute' => 'NOKPPENERIMA',
                'visible' => in_array('NOKPPENERIMA', $visible),
            ],
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
                'value' => 'createdByUser.NAMA', //user will see name instead of id
                'visible' => in_array('PGNDAFTAR', $visible),
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHDAFTAR',
                'visible' => in_array('TRKHDAFTAR', $visible),
            ],
            [
                'attribute' => 'PGNAKHIR',
                'value' => 'updatedByUser.NAMA', //user will see name instead of id
                'visible' => in_array('PGNAKHIR', $visible),
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
                'visible' => in_array('TRKHAKHIR', $visible),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Tindakan',
                'template' => "{cetak} {view} {update} {active}{inactive}",
                'headerOptions' => ['style' => 'width:80px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'cetak' => function ($url, $model) {
                        // if ($model->IDMODULE!="SRT" || $model->IDMODULE!="ULV" || $model->IDMODULE!="PTP" || $model->IDMODULE!="LVC"){
                            return Html::a('<span class="fa fa-print"></span>', ['print', 'id' => $model->NOSIRI], ['target' => '_blank']);
                        // }
                    },
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
                    'cetak' => function($model){
                        if ($model->IDMODULE=="SRT" || $model->IDMODULE=="ULV" || $model->IDMODULE=="PTP" || $model->IDMODULE=="LVC"){
                            $perRead = $model->IDMODULE . "-read"; 
                            return \Yii::$app->access->can($perRead);
                        }
                    },   
                    'view' =>   \Yii::$app->access->can($perRead),
                    'update' => \Yii::$app->access->can($perWrite),
                    'active' => function ($model) {
                        $perWrite = $model->IDMODULE . "-write"; 
                        return \Yii::$app->access->can($perWrite) && ($model->STATUS == common\utilities\OptionHandler::STATUS_TIDAK_AKTIF);
                    },
                    'inactive' => function ($model) {
                        $perWrite = $model->IDMODULE . "-write"; 
                        return \Yii::$app->access->can($perWrite) && ($model->STATUS == common\utilities\OptionHandler::STATUS_AKTIF);
                    },
                // }
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

