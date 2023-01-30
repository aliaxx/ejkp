<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = 'Paparan';
$this->params['breadcrumbs'] = [
    ['label' => '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>', 'url' => ['index']],
    'Paparan',
];
?>

<div>
    <?= '<?php' ?> $form = ActiveForm::begin(); ?>
    <div class="action-buttons">
        <?= '<?=' ?> Html::submitButton('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', [
            'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
            'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
        ]) ?>
    </div>
    <?= '<?php' ?> ActiveForm::end(); ?>

    <?= '<?= ' ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if ($column->name == 'status') {
            echo "            [\n";
            echo "                'attribute' => '" . $column->name . "',\n";
            echo "                'value' => \common\utilities\OptionHandler::resolve('status', \$model->status),\n";
            echo "            ],\n";
        } elseif ($column->name == 'pgndaftar') {
            echo "            [\n";
            echo "                'attribute' => '" . $column->name . "',\n";
            echo "                'value' => \$model->createdByUser->nama,\n";
            echo "            ],\n";
        } elseif ($column->name == 'pgnakhir') {
            echo "            [\n";
            echo "                'attribute' => '" . $column->name . "',\n";
            echo "                'value' => \$model->updatedByUser->nama,\n";
            echo "            ],\n";
        } elseif (in_array($column->name, ['trkhdaftar', 'trkhakhir'])) {
            echo "            '" . $column->name . ":datetime',\n";
        } else {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>
        ],
    ]) ?>
</div>
