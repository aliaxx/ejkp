<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Pengguna */

$this->title = 'Paparan Maklumat';
$this->params['breadcrumbs'] = [
    'Kawalan Pengguna',
    'Penyelenggaraan Pengguna',
    ['label' => 'Profil Pengguna', 'url' => ['index']],
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
            'ID',
            'CUSTOMERID',
            'USERNAME',                                                     
            'NAMA',
            'NOKP',
            // 'SUBUNIT',
            [
                'attribute' => 'SUBUNIT',
                'value' => isset($model->subunit0->PRGN)?$model->subunit0->PRGN:null,
            ],
            'EMAIL',
            [
                'attribute' => 'PERANAN',
                'value' => \common\utilities\OptionHandler::resolve('PERANAN', $model->PERANAN),
            ],
            [
                'attribute' => 'DATA_FILTER',
                'value' => \common\utilities\OptionHandler::resolve('DATA_FILTER', $model->DATA_FILTER),
            ],
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
                'attribute' => 'PGNAKHIR',
                'value' => $model->updatedByUser->NAMA,
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
            ],
        ],
    ]) ?>
</div>
