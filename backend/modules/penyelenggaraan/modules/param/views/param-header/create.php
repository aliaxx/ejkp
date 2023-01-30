<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\parameter\models\ParamHeader */

$this->title = 'Rekod Baru';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    'Parameter Carian',
    ['label' => 'Carian Kumpulan', 'url' => ['index']],
    'Rekod Baru',
];
?>

<div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
