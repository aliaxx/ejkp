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
            'label' => 'Maklumat Lawatan Pemeriksaan',
            'url' => ['view', 'id' => $model->NOSIRI],
            'active' => Yii::$app->controller->action->id == 'view',
        ],
        [
            'label' => 'Penggredan Tandas',
            'url' => ['gredtandas', 'nosiri' => $model->NOSIRI],
            'active' => Yii::$app->controller->action->id == 'gredtandas',
        ], 
        [
            'label' => 'Gambar',
            'url' => ['gambar', 'nosiri' => $model->NOSIRI],
            'active' => Yii::$app->controller->action->id == 'gambar',
        ],   
    ],
]) ?>