<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\parameter\models\ParamDetail */

$this->title = 'Rekod Baru';
$this->params['breadcrumbs'] = [
    'Parameter',
    'Perundangan',
    ['label' => 'Seksyen Kesalahan', 'url' => ['index']],
    'Rekod Baru',
];
?>

<div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
