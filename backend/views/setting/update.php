<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */

$this->title = Yii::t('app', 'Update {modelClass}', ['modelClass' => Yii::t('app', 'Setting')]);
?>

<div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
