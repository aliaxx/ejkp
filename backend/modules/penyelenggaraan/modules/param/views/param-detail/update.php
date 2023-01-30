<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\parameter\models\ParamDetail */

$this->title = 'Kemaskini';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    'Parameter Carian',
    ['label' => 'Carian Terperinci', 'url' => ['index']],
    'Kemaskini',
];
?>

<div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
