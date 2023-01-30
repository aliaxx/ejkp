<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\penyelenggaraan\models\Subunit */

$this->title = 'Kemaskini';
$this->params['breadcrumbs'] = [
    'Penyelenggaraan',
    ['label' => 'Subunit', 'url' => ['index']],
    'Kemaskini',
];
?>
<div class="subunit-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
