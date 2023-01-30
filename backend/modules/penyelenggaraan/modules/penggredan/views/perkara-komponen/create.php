<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\parameter\models\KesalahanButir */

$this->title = 'Rekod Baru';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    'Parameter Penggredan Premis @ Tandas',
    ['label' => 'Komponen', 'url' => ['index']],
    'Rekod Baru',
];
?>

<div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
