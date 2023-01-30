<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use common\utilities\OptionHandler;
/* @var $this yii\web\View */
/* @var $model backend\modules\penyelenggaraan\models\Lokaliti */

$this->title = 'Paparan Maklumat';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    ['label' => 'Lokaliti', 'url' => ['index']],
    'Paparan Maklumat',
];

?>
<div class="lokaliti-view">

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
            'IDMUKIM',
            'PRGN',
            [
                'attribute' => 'STATUS',
                'value' => \common\utilities\OptionHandler::resolve('STATUS', $model->STATUS),
            ],
            
            [
                'attribute' => 'PGNDAFTAR',
                'value' => function ($model){
                    return (!empty($model->createdByUser->NAMA))? $model->createdByUser->NAMA: null;
                    }
            ],
            'TRKHDAFTAR:datetime',
            [
                'attribute' => 'PGNAKHIR',
                'value' => function ($model){
                    return (!empty($model->createdByUser->NAMA))? $model->createdByUser->NAMA: null;
                    }
            ],
            'TRKHAKHIR:datetime',
            'IDZONAM',
        ],
    ]) ?>

</div>
