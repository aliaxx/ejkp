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
            'label' => 'Barang Sitaan',
            'url' => ['sitaan', 'nosiri' => $model->NOSIRI],
            'active' => in_array(Yii::$app->controller->action->id, ['sitaan', 'sitaan-view']), //sitaan is filename
        ],
    ],
]) ?>