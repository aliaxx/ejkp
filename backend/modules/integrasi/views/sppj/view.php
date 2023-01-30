<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii2assets\printthis\PrintThis;

use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;

use backend\modules\integrasi\models\Sewa;


/* @var $this yii\web\View */
/* @var $model backend\modules\integrasi\models\Sewa */

$this->title = 'Paparan Maklumat';
$this->params['breadcrumbs'] = [
    'Integrasi',
    ['label' => 'Integrasi SPPJ', 'url' => ['index']],
    $this->title,
];

$lawatan = $model->lawatan;
// foreach ($model->lawatan as $lawatan){
//     return !empty($lawatan->premis->PRGN) ? $lawatan->premis->PRGN : null;
// }  

// var_dump($lawatan->premis->PRGN);
// exit();
?>

<div>
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['index'], [
            'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
            'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
        ]) ?>
    </div>

<div style="float:left; width:50%;">
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Kompaun</h5></th></tr>
    </table>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'NOKMP',
            'NOICMILIK',
            'NODAFTAR',
            'TRKHKMP',
            'TRFKMP',
            'KODAKTA',
            'KODSALAH',
            'TRKHBAYAR',
            'KAUNTER',
        ],
    ])?>
</div>

<div style="float:left; width:50%;padding-left:5px">
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Lawatan Pemeriksaan</h5></th></tr>
    </table>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'No. Siri',
                'value' => !empty($model->lawatan->NOSIRI) ? $model->lawatan->NOSIRI:null,
            ],
            [
                'label' => 'No. Lot',
                'value' => !empty($model->lawatan->NOLOT) ? $model->lawatan->NOLOT:null,
            ],
            [
                'label' => 'Bangunan',
                'value' => !empty($model->lawatan->BANGUNAN) ? $model->lawatan->BANGUNAN:null,
            ],
            [
                'label' => 'Taman',
                'value' => function ($model){
                    return !empty($model->lawatan->TAMAN) ? $model->lawatan->TAMAN:null;
                }
            ],
            [
                'label' => 'Nama Pesalah',
                'value' => function ($model){
                    return !empty($model->lawatan->NAMAPESALAH) ? $model->lawatan->NAMAPESALAH:null;
                }
            ],
            [
                'label' => 'Jenis Premis',
                'value' => !empty($lawatan->premis->PRGN) ? $lawatan->premis->PRGN: null,
            ],
            [
                'label' => 'Tarikh Salah',
                'value' => !empty($model->lawatan->TRKHSALAH) ? $model->lawatan->TRKHSALAH:null,
            ],
            [
                'label' => 'Liputan',
                'value' => !empty($lawatan->liputan->PRGN) ? $lawatan->liputan->PRGN : null,
            ],
            [
                'label' => 'Tindakan',
                'value' => !empty($lawatan->tindakan->PRGN) ? $lawatan->tindakan->PRGN : null,
            ],
            [
                'label' => 'Sebab Kompaun Tidak Diberi',
                'value' => !empty($lawatan->sebab->PRGN) ? $lawatan->sebab->PRGN : null,
            ],
            [
                'label' => 'Bil. Bekas Musnah',
                'value' => !empty($model->lawatan->BILBEKASMUSNAH) ? $model->lawatan->BILBEKASMUSNAH : null,
            ],
            [
                'label' => 'Latitud',
                'value' => !empty($model->lawatan->LATITUDE) ? $model->lawatan->LATITUDE : null,
            ],
            [
                'label' => 'Longitud',
                'value' => !empty($model->lawatan->LONGITUDE) ? $model->lawatan->LONGITUDE : null,
            ],
        ],
    ])?>
</div>