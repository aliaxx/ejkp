<?php

// use backend\modules\makanan\models\SampelHandswab;
use backend\modules\makanan\utilities\OptionHandler;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View $this
 * @var SampelMakanan $model
 */

$this->title = 'Penggunaan Racun';
$this->params['breadcrumbs'] = [
    'Pencegahan Vektor',
    ['label' => 'Semburan Termal (SRT)', 'url' => ['index']],
    $model->NOSIRI,
    ['label' => $this->title, 'url' => ['/vektor/srt/racun', 'nosiri' => $model->NOSIRI]],
    'Paparan Maklumat'
];
?>

<div>
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['racun', 'nosiri' => $model->NOSIRI], [ //sampel is action -NOR22092022
            'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
            'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
        ]) ?>
    </div>

    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>

    <br><br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'NOSIRI' => 'ST',
            [
                'attribute' => 'ID_PENGGUNAANRACUN',
                'value' => function ($model) {
                    return isset($model->penggunaanRacun->PRGN)?$model->penggunaanRacun->PRGN:null;                   
                },
            ],
            [
                'attribute' => 'ID_JENISRACUNSRTULV',
                // 'value' => 'jenisRacun.PRGN', 
                'value' => function ($model) {
                    return isset($model->jenisRacun->PRGN)?$model->jenisRacun->PRGN:null;                   
                },
            ],
            [
                'attribute' => 'ID_JENISPELARUT',
                // 'value' => 'jenisPelarut.PRGN', 
                'value' => function ($model) {
                    return isset($model->jenisPelarut->PRGN)?$model->jenisPelarut->PRGN:null;                   
                },
            ],
            'BILCAJ',
            'BILMESIN',
            [
                'format' => 'decimal',
                'attribute' => 'AMAUNRACUN',
            ],
            [
                'format' => 'decimal',
                'attribute' => 'AMAUNPELARUT',
            ],
            [
                'format' => 'decimal',
                'attribute' => 'AMAUNPETROL',
            ],
            [
                'attribute' => 'PGNDAFTAR',
                'value' => function ($model) {
                    return isset($model->createdByUser->NAMA)?$model->createdByUser->NAMA:null;                   
                },
            ],
            'TRKHDAFTAR:datetime',
            [
                'attribute' => 'PGNAKHIR',
                'value' => function ($model) {
                    return isset($model->createdByUser->NAMA)?$model->createdByUser->NAMA:null;                   
                },
            ],
            'TRKHAKHIR:datetime',
        ],
    ]) ?>
</div>