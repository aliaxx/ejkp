<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\penyelenggaraan\models\Lokaliti */

// $this->title = 'Update Lokaliti: ' . $model->ID;
// $this->params['breadcrumbs'][] = ['label' => 'Lokalitis', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'ID' => $model->ID]];
// $this->params['breadcrumbs'][] = 'Update';

$this->title = 'Kemaskini Maklumat';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    ['label' => 'Lokaliti', 'url' => ['index']],
    // $this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]],
    $this->title
];
?>
<div class="lokaliti-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
