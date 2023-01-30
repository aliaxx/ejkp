<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\premis\models\PenggredanPremis */

$this->title = 'Rekod Baru';
$this->params['breadcrumbs'] = [
    ['label' => 'Penggredan Premis Makanan', 'url' => ['index']],
    'Rekod Baru',
];
?>

<div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

