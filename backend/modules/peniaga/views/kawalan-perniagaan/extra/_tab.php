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
            'label' => 'Gerai/Petak',
            'url' => ['gerai', 'nosiri' => $model->NOSIRI],
            'active' => in_array(Yii::$app->controller->action->id, ['gerai', 'gerai-view'])
        ],
        [
            'label' => 'Gambar',
            'url' => ['gambar', 'nosiri' => $model->NOSIRI],
            'active' => Yii::$app->controller->action->id == 'gambar',
        ],
        
        
        
        
    ],
]) ?>