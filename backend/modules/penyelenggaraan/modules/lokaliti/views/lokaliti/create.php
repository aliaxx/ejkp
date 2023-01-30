<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\penyelenggaraan\models\Lokaliti */

$this->title = 'Create Lokaliti';
$this->params['breadcrumbs'][] = ['label' => 'Lokalitis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lokaliti-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
