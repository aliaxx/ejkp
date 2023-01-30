<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\premis\models\PenggredanPremis */

$this->title = 'Kemaskini';
$this->params['breadcrumbs'] = [
    ['label' => 'Penggredan Premis Makanan', 'url' => ['index']],
    'Kemaskini',
];
?>

<div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
