<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CountriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Liputan Semburan';
$this->params['breadcrumbs'] = [
    'Pencegahan Vektor',
    ['label' => 'Semburan Termal (SRT)', 'url' => ['index']],
    $model->NOSIRI,
    ['label' => $this->title, 'url' => ['/vektor/srt/liputan', 'nosiri' => $model->NOSIRI]],
];


?>

<div style="margin-top:61px;"></div>
<?= $this->render('_tab', ['model' => $model]) ?>
<br>

<!-- <h4 style="margin-top:0px;">Liputan Pengabutan</h4> -->
<?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:20px'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Liputan Pengabutan') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">  
                        <div class="row">
                            <div class="col-md-12">
                            <?= $this->render('_liputan-form', [
                                'model' => $model,
                                'form' => $form,
                            ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- button simpan -->
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11 action-buttons">
            <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                'id' => 'action_save2close', 'class' => 'btn btn-success', 'name' => 'action[save2close]', 'value' => 1,
                'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
            ]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>