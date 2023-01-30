<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\penyelenggaraan\models\BacaanKolam */

// $this->title = 'Create Bacaan Kolam';
// $this->params['breadcrumbs'][] = ['label' => 'Bacaan Kolams', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;

$this->title = 'Rekod Baru';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    ['label' => 'Bacaan Air Kolam', 'url' => ['index']],
    'Rekod Baru',
];

?>
<div class="bacaan-kolam-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
