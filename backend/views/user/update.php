<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Kemaskini';
$this->params['breadcrumbs'] = [
    'Pengguna',
    ['label' => 'Pengurusan', 'url' => ['index']],
    'Kemaskini',
];
?>

<div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
