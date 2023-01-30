<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\parameter\models\ParamDetail */

$this->title = 'Paparan Maklumat';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    'Perundangan',
    ['label' => 'Seksyen Kesalahan', 'url' => ['index']],
    'Paparan Maklumat',
];
?>

<div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="action-buttons">
        <?= Html::submitButton('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', [
            'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
            'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'KODAKTA',
                'value' => $model->akta->PRGN, //get function from models/ParamHeader.php..paramHeader tu mmg hurufkecil&besar
            ],
            'KODSALAH',
            'SEKSYEN',
            'PRGNSEKSYEN',
            'PRGN1',
            'PRGN2',
            'PRGNDENDA:ntext',
            [
                'attribute' => 'STATUS',
                'value' => \common\utilities\OptionHandler::resolve('STATUS', $model->STATUS),
            ],
            [
                'attribute' => 'PGNDAFTAR',
                'value' => $model->createdByUser->NAMA,
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHDAFTAR',
            ],
            [
                'attribute' => 'pgnakhir',
                'value' => $model->updatedByUser->NAMA,
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
            ],
        ],
    ]) ?>
</div>
