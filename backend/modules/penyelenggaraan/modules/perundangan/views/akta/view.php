<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\parameter\models\ParamHeader */

$this->title = 'Paparan Maklumat';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    'Perundangan',
    ['label' => 'Akta', 'url' => ['index']],
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
            'KODAKTA',
            'PRGN:ntext',
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
                'label' => 'Tarikh Daftar', 
            ],
            [
                'attribute' => 'PGNAKHIR',
                'value' => $model->updatedByUser->NAMA,
            ],
            [
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
                'label' => 'Tarikh Kemaskini Terakhir',
            ],
        ],
    ]) ?>
</div>
