<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\peniaga\models\KawalanPerniagaan */

// $this->title = 'Kawalan Perniagaan';

$this->title = 'Rekod Baru';
// $this->params['breadcrumbs'] = [
// 'Mutu Makanan',
// ['label' => 'Pemeriksaan Kolam', 'url' => ['index']],
// ['label' => $this->title],
// ];

$this->params['breadcrumbs'] = [
    $titleMain,
    ['label' => $title, 'url' => ['index']],
    ['label' => $this->title],
    ];
?>

<div>
    <?= $this->render('_form', [
        'model' => $model,
        'idModule' => $idModule,
    ]) ?>
</div>
