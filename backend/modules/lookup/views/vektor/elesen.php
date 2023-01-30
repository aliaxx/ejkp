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

$this->title = 'Integrasi Lesen';
$this->params['breadcrumbs'][] = 'Integrasi Lesen';

$this->registerCss("
.top-scroll {position:fixed;top:0;right:0;z-index:3;}
");

// Atribut lanjutan
$extender = \codetitan\widgets\GridNav::formatExtender(
    Yii::$app->controller->route,
    ['NO_AKAUN','ID_PERMOHONAN','NO_KP_PEMOHON','NAMA_PEMOHON','NAMA_SYARIKAT','NO_DFT_SYKT','TARIKH_PERMOHONAN','JENIS_PREMIS',
    'ALAMAT_PREMIS1','ALAMAT_PREMIS2','ALAMAT_PREMIS3','POSKOD','STATUS_PERMOHONAN','TARIKH_BATAL_TANGGUH', 'KUMPULAN_LESEN', 'KETERANGAN_KUMPULAN',
    'KATEGORI_LESEN','KETERANGAN_KATEGORI','JENIS_LESEN', 'AMAUN_LESEN', 'LOKASI_MENJAJA','JENIS_JUALAN','KAWASAN', 'ID_KAWASAN','JENIS_JAJAAN',],
    ['NO_AKAUN', 'ID_PERMOHONAN', 'NO_KP_PEMOHON', 'NAMA_PEMOHON', 'NO_DFT_SYKT','NAMA_SYARIKAT','JENIS_LESEN',],
    true
);
$visible = $extender['visible'];

$dropdownActionItems = [];
$dropdownActionItems[] = Html::submitButton('Cetak CSV', [
    'id' => 'action_export-csv', 'name' => 'action[export-csv]',
    'class' => 'btn-dropdown btn btn-default', 'value' => 1, 'data-pjax' => 0,
]);
$dropdownActionItems[] = Html::submitButton('Cetak PDF', [
    'id' => 'action_export-csv', 'name' => 'action[export-pdf]',
    'class' => 'btn-dropdown btn btn-default', 'value' => 1, 'data-pjax' => 0,
]);
$dropdownActionItems[] = Html::button('Cetak Dokumen Pilihan', [
    'class' => 'btn-dropdown btn btn-default',
    'onclick' => 'printBulk()',
]);
?>

<div>
    <?php $output = GridView::widget([
        'id' => 'primary-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-striped table-condensed table-hover table-responsive'],
        'layout' => '{items}',
        'columns' => [
            // [
            //     'class' => 'yii\grid\CheckboxColumn',
            //     'headerOptions' => ['class' => 'text-center', 'style' => 'width:25px'],
            //     'contentOptions' => ['class' => 'text-center'],
            // ],
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
                            'onclick' => "parent.lookup.select('" . Yii::$app->request->get('target') . "', '" . $model->NO_AKAUN . "', '" . $model->NO_AKAUN . "')",
                        ];
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', 'javascript:void(0)', $options);
                    },
                ],
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

// var reqid = '" . strtotime('+8 hours', time()) . Yii::$app->user->id . "';

// function requestPrint(items, pointer)
// {
//     $('#printingBtnGroup').attr('disabled', true);
//     try {
//         $.post('" . Url::to(['/kpp/pengurusan/print-bulk']) . "', { list: items, pointer: pointer, reqid: reqid }, function (data) {
//             if (data.link) {
//                 $('#printingBtnGroup').attr('disabled', false);
//                 alert('File KPP telah siap untuk dimuat turun.');
//                 reqid = data.newreqid;
//                 window.open(data.link, '_blank');
//             } else {
//                 requestPrint(items, data.next);
//             }
//         });
//     } catch(err) {
//         $('#printingBtnGroup').attr('disabled', false);
//     }
// }

// function printBulk()
// {
//     let kppSelected = [];
//     let all = false;

//     if ($('input[name=\'selection_all\']').is(':checked')) {
//         all = confirm('Adakah anda ingin mencetak semua KPP rekod atau yang di pilih sahaja dalam senarai ini?');
//     }
    
//     if (all) {
//         requestPrint('all', 0);
//     } else {
//         $('input[name=\'selection[]\']:checked').each(function (key, item) {
//             kppSelected[key] = this.value;
//         });

//         if (kppSelected.length > 0) {
//             requestPrint(kppSelected, 0);
//         } else {
//             alert('Anda perlu memilih sekurang-kurangnya 1 rekod untuk mencetak.');
//         }
//     }
// }

// function batalKpp(norujukankpp)
// {
//     if (confirm('Rekod KPP ini akan dibatalkan. Teruskan?')) {
//         let sebabBatal = prompt(\"Rekod KPP ini akan dibatalkan.\\nSila nyatakan sebab pembatalan.\");
        
//         if (sebabBatal === null) {
//             alert('Pembatalan rekod KPP tidak diteruskan.');
//             return;
//         }

//         if (/\S/.test(sebabBatal)) {
//             var url = '" . Url::to(['/kpp/pengurusan/batal']) . "';
//             var form = $('<form id=\"dynamicBatalForm\" action=\"' + url + '\" method=\"post\">' +
//             '<input type=\"hidden\" name=\"" . Yii::$app->request->csrfParam . "\" value=\"" . Yii::$app->request->getCsrfToken() . "\" />' +
//             '<input type=\"text\" name=\"id\" value=\"' + norujukankpp + '\" />' +
//             '<input type=\"text\" name=\"sebab\" value=\"' + sebabBatal + '\" />' +
//             '</form>');
//             $('body').append(form);
//             form.submit();
//         } else {
//             alert('Gagal membatalkan rekod KPP. Sebab pembatalan tidak boleh dibiarkan kosong.');
//         }
//     }

//}
", View::POS_END);
