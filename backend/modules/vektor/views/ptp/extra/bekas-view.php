<?php

use backend\modules\vektor\utilities\OptionHandler;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View $this
 * @var SampelMakanan $model
 */

$this->title = 'Bekas Diperiksa';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
['label' => 'Pemeriksaan Tempat Pembiakan Aedes(PTP)', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/vektor/ptp/bekas', 'nosiri' => $model->NOSIRI]],
'Paparan Maklumat'
];

?>

<div>
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['bekas', 'nosiri' => $model->NOSIRI], [ //bekas is action -NOR22092022
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
                'attribute' => 'KAWASAN',
                'value' => function($bekas) {
                    return OptionHandler::resolve('kaw-pembiakan', $bekas->KAWASAN);
                }
            ],
            'JENISBEKAS',
            'BILBEKAS',
            'BILPOTENSI',
            'BILPOSITIF',
            'KEPUTUSAN',
            'PURPA',
            'CATATAN',
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