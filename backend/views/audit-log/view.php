<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

use common\models\Pengguna;
use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Auditlog */

$this->title = 'Paparan Maklumat Log Audit';
$this->params['breadcrumbs'] = [
    'Kawalan Pengguna',
    ['label' => 'Log Audit', 'url' => ['index']],
    'Paparan Maklumat',
];
?>

<div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="action-buttons">
        <?= Html::submitButton('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', [
            'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
            'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            [
                'attribute' => 'TINDAKAN',
                'value' => function ($model) {
                    return OptionHandler::resolve('log-tindakan', $model->TINDAKAN);
                }
            ],
            [
                'attribute' => 'TARIKHMASA',
                'value' => function ($model) {
                    return DateTimeHelper::convert($model->TARIKHMASA, false, true);
                }
            ],
            'NAMATABLE',
            'URLMENU',
            [
                'attribute' => 'PENGGUNA',
                'value' => function ($model) {
                    return ($model->user)? $model->user->NAMA : null;
                }
            ],
            'DATALAMA',
            'DATA'
        ],
    ]) ?>
</div>
<?php
