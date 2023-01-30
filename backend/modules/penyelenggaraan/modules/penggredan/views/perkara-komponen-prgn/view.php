<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $model backend\modules\parameter\models\KesalahanButir */

$this->title = 'Paparan Maklumat';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    'Parameter Penggredan Premis @ Tandas',
    ['label' => 'Keterangan', 'url' => ['index']],
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
                'attribute' => 'JENIS',
                'value' => OptionHandler::resolve('premis-tandas', $model->JENIS),
            ],
            [
                'attribute' => 'KODPERKARA',
                'value' => $model->kodperkara0->KODPERKARA . ' - ' . $model->kodperkara0->PRGN,
            ],
            [
                'attribute' => 'KODKOMPONEN',
                'value' => $model->kodkomponen0->KODKOMPONEN . ' - ' . $model->kodkomponen0->PRGN,
            ],
            'KODPRGN',
            'MARKAH',
            'PRGN:ntext',
            [
                'attribute' => 'STATUS',
                'value' => OptionHandler::resolve('STATUS', $model->STATUS),
            ],
            [
                'attribute' => 'PGNDAFTAR',
                'value' => function ($model){
                    return (!empty($model->createdByUser->NAMA))? $model->createdByUser->NAMA: null;
                }
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHDAFTAR',
            ],
            [
                'attribute' => 'PGNAKHIR',
                'value' => function ($model){
                    return (!empty($model->updatedByUser->NAMA))? $model->updatedByUser->NAMA: null;
                }
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
            ],
        ],
    ]) ?>
</div>
