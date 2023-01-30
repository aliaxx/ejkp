<?php

use backend\modules\vektor\utilities\OptionHandler;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View $this
 * @var SampelMakanan $model
 */

$this->title = 'Kompaun/Notis';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
['label' => 'Pemeriksaan Tempat Pembiakan Aedes(PTP)', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/vektor/ptp/penguatkuasaan', 'nosiri' => $model->NOSIRI]],
'Paparan Maklumat Kompaun'
];

?>

<div>
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['penguatkuasaan', 'nosiri' => $model->NOSIRI], [ //bekas is action -NOR22092022
            'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
            'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
        ]) ?>
    </div>

    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>
    <br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'NOSIRI',
            [
                'attribute' => 'JENIS',
                'value' => function($model) {
                    return OptionHandler::resolve('jenis-tindakan', $model->JENIS);
                }
            ],
            'NOLOT',
            'BANGUNAN',
            'TAMAN',
            'NAMAPESALAH',
            [
                'attribute' => 'ID_JENISPREMIS',
                'value' => isset($model->premis->PRGN)?$model->premis->PRGN:null,
            ],
            [
                'attribute' => 'TRKHSALAH',
                'value' => date('d/m/Y H:i', strtotime($model->TRKHSALAH)),
            ],
            [
                'attribute' => 'LIPUTAN',
                'value' => isset($model->liputan->PRGN)?$model->liputan->PRGN:null,
            ],
            [
                'attribute' => 'ID_JENISPEMBIAKAN',
                'value' => isset($model->jenisPembiakan->PRGN)?$model->jenisPembiakan->PRGN:null,
            ],
            'NOSAMPEL',
            [
                'attribute' => 'ID_TINDAKAN',
                'value' => isset($model->tindakan->PRGN)?$model->tindakan->PRGN:null,
            ],
            [
                'attribute' => 'ID_SEBABNOTIS',
                'value' => isset($model->sebab->PRGN)?$model->sebab->PRGN:null,
            ],
            'BILBEKASMUSNAH',
            'LATITUDE', 
            'LONGITUDE',
            [
                'attribute' => 'PGNDAFTAR',
                'value' => $model->createdByUser->NAMA,
            ],
            'TRKHDAFTAR:datetime',
            [
                'attribute' => 'PGNAKHIR',
                'value' => $model->createdByUser->NAMA,
            ],
            'TRKHAKHIR:datetime',
        ],
    ]) ?>
</div>