<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

use backend\modules\peniaga\utilities\OptionHandler;
use backend\modules\penyelenggaraan\models\ParamDetail;
use backend\modules\peniaga\models\LawatanPasukan;

/* @var $this yii\web\View */
/* @var $model backend\modules\peniaga\models\KawalanPerniagaan */

$this->title = $model->NOSIRI;
$this->params['breadcrumbs'][] = ['label' => 'Penggredan Premis Makanan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="action-buttons">
       
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['/premis/anugerah/carian'],
        ['class' => 'btn btn-danger']) ?>
        <span class="pull-right">
        <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar', ['/premis/anugerah/create', 'id'=>$model->NOSIRI],
        ['class' => 'btn btn-success']) ?>
        <p>
        </span>
    </div>

    
    <?php ActiveForm::end(); ?>
<table style="height:40px; width:100%;">
    <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Premis</h5></th></tr>
</table>
<div style="float:left; width:50%;">   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'NOSIRI',
            [
                'format' => 'datetime',
                'attribute' => 'TRKHMULA',
            ],
            [
                'attribute' => 'JENISPREMIS',
                'value' => OptionHandler::resolve('jenis-premis', $model->JENISPREMIS),
            ],
            [
                'label' => 'Nama Syarikat',
                'attribute' => 'NAMASYARIKAT',
                'value' => function ($model){
                    return (!empty($model->pemilik0->NAMASYARIKAT))? $model->pemilik0->NAMASYARIKAT: null;
                }
            ],
            [
                'label' => 'Alamat Premis',
                'attribute' => 'ALAMAT1',
                'value' => function ($model){
                    return (!empty($model->pemilik0->ALAMAT1.$model->pemilik0->ALAMAT2.$model->pemilik0->ALAMAT3))? $model->pemilik0->ALAMAT1.', '.$model->pemilik0->ALAMAT2.', '.$model->pemilik0->ALAMAT3: null;
                }
            ],
            'CATATAN',
            [
                'attribute' => 'PGNAKHIR',
                'value' => $model->updatedByUser->NAMA,
            ],
            'TRKHAKHIR:datetime',
        ],
    ]) ?>
</div>
<div style="float:right; width:50%;">   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'IDMODULE',
            [
                'format' => 'datetime',
                'attribute' => 'TRKHTAMAT',
            ],
            [
                'attribute' => 'NOLESEN',
                'label' => 'No. Lesen',
                'value' => function ($model){
                    return (!empty($model->pemilik0->NOLESEN))? $model->pemilik0->NOLESEN: null;
                }
            ],
            [
                'label' => 'Nama Pemilik/Pemohon',
                'attribute' => 'NAMAPEMOHON',
                'value' => function ($model){
                    return (!empty($model->pemilik0->NAMAPEMOHON))? $model->pemilik0->NAMAPEMOHON: null;
                }
            ],
            'NOADUAN',
            'STATUS',
            [
                'attribute' => 'PGNAKHIR',
                'value' => $model->updatedByUser->NAMA,
            ],
            'TRKHAKHIR:datetime',
        ],
    ]) ?>
</div>

<table style="height:40px; width:100%;">
    <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Kompaun</h5></th></tr>
</table>
<div style="float:left; width:50%;">   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        ]
    ]) ?>
</div>
<div style="float:right; width:50%;">   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        ],
    ]) ?>
</div>
<table style="height:40px; width:100%;">
    <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Notis</h5></th></tr>
</table>
<div style="float:left; width:50%;">   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        ]
    ]) ?>
</div>
<div style="float:right; width:50%;">   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        ],
    ]) ?>
</div>
