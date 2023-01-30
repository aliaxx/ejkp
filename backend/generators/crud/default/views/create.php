<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = 'Rekod Baru';
$this->params['breadcrumbs'] = [
    ['label' => '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>', 'url' => ['index']],
    'Rekod Baru',
];
?>

<div>
    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
