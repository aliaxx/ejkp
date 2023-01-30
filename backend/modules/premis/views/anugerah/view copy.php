<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\premis\models\Anugerah */

$this->title = $model->NOLESEN;
$this->params['breadcrumbs'][] = ['label' => 'Anugerahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="anugerah-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'NOLESEN' => $model->NOLESEN, 'NOSYARIKAT' => $model->NOSYARIKAT], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'NOLESEN' => $model->NOLESEN, 'NOSYARIKAT' => $model->NOSYARIKAT], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'NOLESEN',
            'NOSYARIKAT',
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
