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
['label' => 'Aktiviti Larvaciding', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/vektor/lvc/larvisid', 'nosiri' => $model->NOSIRI]],
'Paparan Maklumat'
];

?>

<div>
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['larvisid', 'nosiri' => $model->NOSIRI], [ //bekas is action -NOR22092022
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
                'attribute' => 'AKTIVITI',
                'value' => function($larvisid) {
                    return OptionHandler::resolve('aktiviti', $larvisid->AKTIVITI);
                }
            ],
            'V_SASARANPREMIS',
            'V_BILPREMIS',
            'V_BILBEKAS',
            [
                'attribute' => 'V_ID_JENISRACUN',
                'value' => $model->racun->PRGN,
            ],
            'V_JUMRACUN',
            'V_BILMESIN',
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