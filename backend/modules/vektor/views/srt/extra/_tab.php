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
            'label' => 'Sasaran Semburan',
            'url' => ['sasaran', 'nosiri' => $model->NOSIRI], //sasaran = action , nosiri = parameter -NOR22092022
            'active' => in_array(Yii::$app->controller->action->id, ['sasaran']), //sampel is action -NOR22092022
        ],
        [
            'label' => 'Liputan Pengabutan',
            'url' => ['liputan', 'nosiri' => $model->NOSIRI], //sasaran = action , nosiri = parameter -NOR22092022
            'active' => in_array(Yii::$app->controller->action->id, ['liputan']), //sampel is action -NOR22092022
        ],
        [
            'label' => 'Penggunaan Racun Serangga',
            'url' => ['racun', 'nosiri' => $model->NOSIRI], //sampel is controller -NOR22092022
            'active' => in_array(Yii::$app->controller->action->id, ['racun', 'racun-view']), //sampel is action -NOR22092022
        ],
        [
            'label' => 'Gambar',
            'url' => ['gambar', 'nosiri' => $model->NOSIRI],
            'active' => Yii::$app->controller->action->id == 'gambar',
        ], 
    ],
]) ?>