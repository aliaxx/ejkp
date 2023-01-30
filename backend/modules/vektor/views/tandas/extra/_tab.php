<?php

use yii\bootstrap\Tabs;
use yii\web\View;

/**
 * @var View $this
 */
?>
<?php if ($model->IDMODULE == 'PTS'): ?>
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

<?php elseif ($model->IDMODULE == 'PPM'): ?>
    <?= Tabs::widget([
    'items' => [
        [
            'label' => 'Maklumat Lawatan Pemeriksaan',
            'url' => ['view', 'id' => $model->NOSIRI],
            'active' => Yii::$app->controller->action->id == 'view',
        ],
        [
            'label' => 'Penggredan Premis',
            'url' => ['gredpremis', 'nosiri' => $model->NOSIRI],
            'active' => Yii::$app->controller->action->id == 'gredpremis',
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
<?php endif ?>