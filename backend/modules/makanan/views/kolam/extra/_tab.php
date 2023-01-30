<?php

use yii\bootstrap\Tabs;
use yii\web\View;

/**
 * @var View $this
 */
?>

<?= Tabs::widget([
    'items' => [
        [
            'label' => 'Maklumat Operasi',
            'url' => ['view', 'id' => $model->NOSIRI],
            'active' => Yii::$app->controller->action->id == 'view',
        ],
        [
            'label' => 'Parameter Air Kolam',
            'url' => ['kolam', 'nosiri' => $model->NOSIRI],
            'active' => Yii::$app->controller->action->id == 'kolam',
        ], 
        [
            'label' => 'Gambar',
            'url' => ['gambar', 'nosiri' => $model->NOSIRI],
            'active' => Yii::$app->controller->action->id == 'gambar',
        ],   
    ],
]) ?>
