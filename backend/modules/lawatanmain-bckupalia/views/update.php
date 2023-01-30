<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\peniaga\models\KawalanPerniagaan */

$this->title = 'Kemaskini Maklumat';
$this->params['breadcrumbs'] = [
    $titleMain,
    ['label' => $title, 'url' => ['index']],
    $model->NOSIRI,
    ['label' => $this->title],
    ];
?>


<?= $this->render('_form', [
    'model' => $model,
    'idModule' => $idModule,
]) ?>
