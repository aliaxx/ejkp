<?php

use backend\modules\vektor\utilities\OptionHandler;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View $this
 * @var SampelMakanan $model
 */

$this->title = 'Jenis Bekas';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
['label' => 'Pemeriksaan Tempat Pembiakan Aedes(PTP)', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/vektor/ptp/jenis-bekas', 'nosiri' => $model->NOSIRI]],
'Paparan Maklumat Kompaun'
];

?>

<div>
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['jenis-bekas', 'nosiri' => $model->NOSIRI, 'nosampel' => $model->NOSAMPEL, 'id' => $model->ID], [
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
            // 'ID', 
            'NOSAMPEL', 
            'JENISBEKAS', 
            'BILBEKAS', 
            'BILPOTENSI', 
            'BILPOSITIF', 
            [
                'attribute' => 'KEPUTUSAN',
                'value' => isset($model->jenisPembiakan->PRGN)?$model->jenisPembiakan->PRGN:null,
            ],
            'PURPA', 
            [
                'attribute' => 'KAWASAN',
                'value' => isset($model->liputan->PRGN)?$model->liputan->PRGN:null,
            ],
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