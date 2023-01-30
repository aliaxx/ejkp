<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Peranan */

$this->title = 'Akses';
$this->params['breadcrumbs'] = [
    'Kawalan Pengguna',
    'Penyelenggaraan Pengguna',
    ['label' => 'Peranan Pengguna', 'url' => ['index']],
    $parentModel->NAMAPERANAN,
    'Capaian',
];

$access = [];
if (isset($parentModel->perananAkses)) {
    foreach ($parentModel->perananAkses as $item) {
        $access[$item->KODAKSES]['read'] = $item->AKSES_LIHAT;
        $access[$item->KODAKSES]['write'] = $item->AKSES_URUS;
    }
}

$models = [];
foreach (Yii::$app->access->getPermissionList() as $id => $name) {
    $models[] = ['ID' => $id, 'name' => $name, 'read' => 0, 'write' => 0];
}

$dataProvider = new \yii\data\ActiveDataProvider([
    'models' => $models,
    'pagination' => false,
]);

$this->registerJs("
    $('[id^=chk-]').on('click', function() {
        var parts = $(this).attr('id').split('-');
        parts.shift();

        var permission = parts.pop();
        var code = parts.join('-');
        if (permission == 'read') {
            if (!$(this).prop('checked')) {
                $('#chk-' + code + '-write').prop('checked', false);
            }
        } else if (permission == 'write') {
            if ($(this).prop('checked')) {
                $('#chk-' + code + '-read').prop('checked', true);
            }
        }
    });

", \yii\web\View::POS_END);
?>

<div>
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= \codetitan\widgets\ActionBar::widget([
            'permissions' => ['save2close' => Yii::$app->access->can('peranan-write')],
        ]) ?>

        <div class="form-group">
            <div class="col-lg-10">
                <?= \yii\grid\GridView::widget([
                    'id' => 'primary-grid',
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table table-bordered table-striped table-condensed table-hover table-responsive'],
                    'layout' => '{items}',
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn', 
                            'headerOptions' => ['class' => 'text-center', 'style' => 'width:25px'],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'format' => 'html',
                            'label' => 'Nama Skrin/Capaian',
                            'attribute' => 'name',
                            'value' => function ($model) {
                                return str_replace('->', '&rarr;', $model['name']);
                            },
                        ],
                        [
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'text-center', 'style' => 'width:80px'],
                            'contentOptions' => ['class' => 'text-center'],
                            'label' => 'Lihat',
                            'attribute' => 'read',
                            'value' => function ($model) use ($access) {
                                $checked = false;
                                if (isset($access[$model['ID']])) {
                                    $checked = $access[$model['ID']]['read'];
                                }

                                return Html::checkbox('chk['.$model['ID'].'][read]', $checked, ['ID' => 'chk-'.$model['ID'].'-read']);
                            },
                        ],
                        [
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'text-center', 'style' => 'width:80px'],
                            'contentOptions' => ['class' => 'text-center'],
                            'label' => 'Urus',
                            'attribute' => 'write',
                            'value' => function ($model) use ($access) {
                                $checked = false;
                                if (isset($access[$model['ID']])) {
                                    $checked = $access[$model['ID']]['write'];
                                }

                                return Html::checkbox('chk['.$model['ID'].'][write]', $checked, ['ID' => 'chk-'.$model['ID'].'-write']);
                            },
                        ],
                    ],
                ]); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-8 action-buttons">
                <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['index'], [
                    'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]',
                    'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
                ]) ?>

                <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                    'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1,
                    'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
                ]) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
