<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\penyelenggaraan\models\LokasiTandasvektor */

$this->title = 'Rekod Baru';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    ['label' => 'Lokasi Tandas', 'url' => ['index']],
    'Rekod Baru',
];
?>
<div class="lokasi-tandasvektor-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
