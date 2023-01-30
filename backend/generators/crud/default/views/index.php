<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? "use yii\widgets\Pjax;\n" : '' ?>
use yii\bootstrap\ActiveForm;

use common\utilities\OptionHandler;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>';
$this->params['breadcrumbs'][] = '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>';
?>

<div>
    <?= '<?php' ?> $form = ActiveForm::begin(); ?>
        <?= '<?=' ?> \codetitan\widgets\ActionBar::widget([
            'target' => 'primary-grid',
            'permissions' => ['new' => Yii::$app->access->can('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-write')],
        ]) ?>
    <div class="action-buttons">
        <?= '<?=' ?> Html::submitButton('<i class="glyphicon glyphicon-plus-sign"></i> Daftar', [
            'id' => 'action_new', 'class' => 'btn btn-primary', 'name' => 'action[new]', 'value' => 1,
            'title' => 'Daftar', 'aria-label' => 'Daftar', 'data-pjax' => 0,
        ]) ?>

        <?= '<?=' ?> $this->render('@backend/views/layouts/_print') ?>
    </div>
    <?= '<?php' ?> ActiveForm::end(); ?>

<?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : '' ?>
<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= '<?php' ?> $output = GridView::widget([
        'id' => 'primary-grid',
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n" : ""; ?>
        'tableOptions' => ['class' => 'table table-bordered table-striped table-condensed table-hover table-responsive'],
        'layout' => '{items}',
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn', 
                'headerOptions' => ['class' => 'text-center', 'style' => 'width:25px'],
                'contentOptions' => ['class' => 'text-center'],
            ],
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (!in_array($column->name, ['status', 'pgndaftar', 'trkhdaftar', 'pgnakhir', 'trkhakhir'])) {
            if (++$count < 6) {
                echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
            } else {
                echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
            }
        } else {
            if ($column->name == 'status') {
                echo "            [\n";
                echo "                'attribute' => '" . $column->name . "',\n";
                echo "                'filter' => \common\utilities\OptionHandler::render('status'),\n";
                echo "                'value' => function (\$model) {\n";
                echo "                    return \common\utilities\OptionHandler::resolve('status', \$model->status);\n";
                echo "                },\n";
                echo "            ],\n";
            } elseif ($column->name == 'pgnakhir') {
                echo "            [\n";
                echo "                'attribute' => '" . $column->name . "',\n";
                echo "                'value' => 'updatedByUser.nama',\n";
                echo "            ],\n";
            } elseif ($column->name == 'trkhakhir') {
                echo "            '" . $column->name . ":datetime',\n";
            }
        }
    }
}
?>
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => "{view} {update} {active}{inactive}",
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'active' => function ($url, $model) {
                        $options = [
                            'title' => 'Aktif',
                            'aria-label' => 'Aktif',
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan ditukar menjadi "Aktif". Teruskan?',
                            'data-method' => 'post',
                        ];

                        return Html::a('<span class="fa fa-toggle-off"></span>', ['delete', <?= $urlParams ?>], $options);
                    },
                    'inactive' => function ($url, $model) {
                        $options = [
                            'title' => 'Tidak Aktif',
                            'aria-label' => 'Tidak Aktif',
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan ditukar menjadi "Tidak Aktif". Teruskan?',
                            'data-method' => 'post',
                        ];

                        return Html::a('<span class="fa fa-toggle-on"></span>', ['delete', <?= $urlParams ?>], $options);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-read'),
                    'update' => \Yii::$app->access->can('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-write'),
                    'active' => function ($model) {
                        return \Yii::$app->access->can('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-write') && ($model->status == OptionHandler::STATUS_TIDAK_AKTIF);
                    },
                    'inactive' => function ($model) {
                        return \Yii::$app->access->can('<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-write') && ($model->status == OptionHandler::STATUS_AKTIF);
                    },
                ],
            ],
        ],
    ]); ?>

    <?= '<?=' ?> \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>
<?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : '' ?>
</div>
