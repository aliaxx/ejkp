<?php

use yii\helpers\Html;
// use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use backend\modules\vektor\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $bekas app\models\Countries */
/* @var $form yii\widgets\ActiveForm */

?>

<!-- <div style="padding-top:20px"> -->
    <?= GridView::widget([
        'id' => 'primary-grid',
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-striped table-condensed table-hover table-responsive'],
        'layout' => '{items}',
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['class' => 'text-center', 'style' => 'width:25px'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            // 'NOSIRI',
            // 'KAWASAN',
            [
                'attribute' => 'KAWASAN',
                // 'headerOptions' => ['style' => 'width:10px'],
                'contentOptions' => ['style' => 'height:40px'],
                'value' => function($bekas) {
                    return OptionHandler::resolve('kaw-pembiakan', $bekas->KAWASAN);
                }
            ],
            'JENISBEKAS',
            'BILBEKAS',
            [
                'attribute' => 'BILPOTENSI',
                'headerOptions' => ['style' => 'height:20px;width:50%'],
            ],
            'BILPOSITIF',
            [
                'attribute' => 'KEPUTUSAN',
                'headerOptions' => ['style' => 'width:50%'],
            ],
            'PURPA',
            'CATATAN',
            // 'PGNDAFTAR',
            // 'TRKHDAFTAR',
            [
                'attribute' => 'PGNAKHIR',
                'value' => 'createdByUser.NAMA', 
            ],
            'TRKHAKHIR:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => "Tindakan",
                'template' => "{view} {update} {delete}",
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'view' => function ($url, $bekas) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open" style="color:green"></i>', ['bekas-view', 'nosiri' => $bekas->NOSIRI, 'ID' => $bekas->ID]);
                    },
                    'update' => function ($url, $bekas) {
                        return Html::a('<i class="glyphicon glyphicon-pencil" style="color:green"></i>', ['bekas', 'nosiri' => $bekas->NOSIRI, 'idbekas' => $bekas->ID]); //'nosiri', 'idsampel' are parameter in controller action -NOR06092022
                    },
                    'delete' => function ($url, $bekas) {
                        return Html::a('<i class="glyphicon glyphicon-trash" style="color:green"></i>', ['bekas-delete'], [
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan dipadamkan. Maklumat bekas akan dikemaskini mengikut tarikh tindakan terkini. Teruskan?', //display onclick -NOR120922
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $bekas->NOSIRI, 'idbekas' => $bekas->ID,
                            ],
                        ]);
                    },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('bekas-read'),
                    'update' => \Yii::$app->access->can('bekas-write'),
                    'delete' => \Yii::$app->access->can('bekas-write'),
                ],
            ],
        ],
    ]); 
    
    ?>

    <!-- <div class="row"> -->
    <div class="form-group">
        <div class="col-md-6">
            <?= DetailView::widget([
                    'model' => $model,
                    'template' => "<tr><th style='width: 33%;'>{label}</th><td>{value}</td></tr>",
                    'attributes' => [
                        'PTP_BILBEKASMUSNAH',
                    ],
                ])
            ?>  
        </div>
        <!-- <div class="col-md-6"> -->
            <div class="col-lg-offset-1 col-lg-5 col-md-6 action-buttons">
                <?= Html::a('Kira Pencapaian', ['kira', 'nosiri' => $bekas->NOSIRI], ['class' => 'btn btn-success', 'style'=>'width:200px'])?>
            </div>
        <!-- </div> -->
    </div>
    <!-- <div> -->

    <h4 style="margin-top:0px;">Daftar Bekas Diperiksa</h4>
    <!-- tak perlu activeform sebab render dari file bekas yg dah ada activeform. else akan interrupt button simpan -NOR12102022 -->
    <!-- <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:0px'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-4 control-label'],
        ],
    ]); ?> -->

    <div class="row">
        <!-- left side -->
        <div class="col-md-6">
            <?= $form->field($bekas, 'KAWASAN')->dropDownList(OptionHandler::render('kaw-pembiakan'), ['prompt' => '']) ?>

            <?= $form->field($bekas, 'BILBEKAS')->textInput(['type' => 'number', 'min' => 0])?>

            <?= $form->field($bekas, 'BILPOSITIF')->textInput(['type' => 'number', 'min' => 0])?>

            <?= $form->field($bekas, 'PURPA')->textInput(['maxlength' => true]) ?>
        </div>

        <!-- right side -->
        <div class="col-md-6">
            <?= $form->field($bekas, 'JENISBEKAS')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($bekas, 'BILPOTENSI')->textInput(['type' => 'number', 'min' => 0])?>

            <?= $form->field($bekas, 'KEPUTUSAN')->textInput(['maxlength' => true]) ?>

            <?= $form->field($bekas, 'CATATAN')->textarea(['rows' => '3']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'PTP_BILBEKASMUSNAH')->textInput([
                'type' => 'number', 
                'min' => ($model->isNewRecord ? 1 : 0),            
                'style' => 'height:35px; width:200px',
            ]) ?>
        </div>
    </div>


    <!-- <?php ActiveForm::end() ?> -->

<!-- </div> -->
