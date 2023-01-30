<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\premis\models\Anugerah */


$this->params['breadcrumbs'] = [
    $titleMain,
    ['label' => $title, 'url' => ['index']],
    $model->NOSIRI,
    'Paparan Maklumat'
    ];

?>
<div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="action-buttons">
       
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['/premis/anugerah/index'],
        ['class' => 'btn btn-danger']) ?>
        </span>
    </div>

    
    <?php ActiveForm::end(); ?>
<div class="anugerah-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'NOLESEN',
            'NOSSM',
            'TAHUN',
            'GRED',
            'CATATAN',
            'STATUS',
            'PGNDAFTAR',
            'TRKHDAFTAR',
            'PGNAKHIR',
            'TRKHAKHIR',
        ],
    ]) ?>

</div>
