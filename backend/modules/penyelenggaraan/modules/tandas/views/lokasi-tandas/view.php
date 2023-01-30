<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $model backend\modules\penyelenggaraan\models\LokasiTandasvektor */

$this->title = 'Paparan Maklumat';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    ['label' => 'Lokasi Tandas', 'url' => ['index']],
    'Paparan Maklumat',
];

?>
<div class="lokasi-tandasvektor-view">

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
            // 'ID',
            [
                'attribute' => 'ID_KATEGORITANDAS',
                'value' => $model->kategori->PRGN,
            ],
            'PRGN',
            [
                'attribute' => 'status',
                'value' => \common\utilities\OptionHandler::resolve('STATUS', $model->STATUS),
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
