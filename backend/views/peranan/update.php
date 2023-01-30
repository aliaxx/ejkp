<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Peranan */

$this->title = 'Kemaskini';
$this->params['breadcrumbs'] = [
    'Kawalan Pengguna',
    'Penyelenggaraan Pengguna',
    ['label' => 'Peranan Pengguna', 'url' => ['index']],
    'Kemaskini',
];
?>

<div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
