<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\penyelenggaraan\modules\lokaliti\models\LokalitiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lokaliti-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDMUKIM') ?>

    <?= $form->field($model, 'PRGN') ?>

    <?= $form->field($model, 'STATUS') ?>

    <?= $form->field($model, 'PGNDAFTAR') ?>

    <?php // echo $form->field($model, 'TRKHDAFTAR') ?>

    <?php // echo $form->field($model, 'PGNAKHIR') ?>

    <?php // echo $form->field($model, 'TRKHAKHIR') ?>

    <?php // echo $form->field($model, 'IDZONAM') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
