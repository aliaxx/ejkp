<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\premis\models\Anugerah */

$this->title = 'Rekod Baru';

$this->params['breadcrumbs'] = [
    $titleMain,
    ['label' => $title, 'url' => ['index']],
    ['label' => $this->title],
    ];
?>

<div>
    <?= $this->render('_form', [
        'lawatan' => $lawatan,
        // 'modelpremis' =>  $modelpremis,
        'model' =>  $model,
    ]) ?>
</div>
