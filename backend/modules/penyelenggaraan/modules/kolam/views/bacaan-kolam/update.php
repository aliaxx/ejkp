<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\penyelenggaraan\models\BacaanKolam */

$this->title = 'Kemaskini';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    ['label' => 'Bacaan Air Kolam', 'url' => ['index']],
    'Kemaskini',
];

?>
<div class="bacaan-kolam-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
