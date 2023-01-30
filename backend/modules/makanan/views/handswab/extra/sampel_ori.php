<?php

use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
// use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;


use backend\modules\makanan\utilities\OptionHandler;
// use backend\modules\makanan\models\Handswab;

// var_dump();
// exit();

$this->title = 'Sampel Handswab';
$this->params['breadcrumbs'] = [
    ['label' => 'Handswab', 'url' => ['index']],
    $model->NOSIRI,
    $this->title,
];

$option['initialPreview'] = [];
$option['initialPreviewConfig'] = [];

// if ($files = $model->getAttachments()) {
//     foreach ($files as $key => $file) {
//         $option['initialPreview'][] = $file;
//         $option['initialPreviewConfig'][] = [
//             // 'size' => filesize(Yii::getAlias('@backend/web/' . Handswab::IMAGE_PATH . '/' . basename($file))),
//             'downloadUrl' => Yii::getAlias('@web/' . Handswab::IMAGE_PATH . '/' . basename($file)),
//             'url' => Url::to([
//                 '/makanan/handswab/file-delete',
//                 'NOSIRI' => $model->NOSIRI,
//                 'filename' => basename($file),
//             ]),
//         ];
//     }
// }

?>

<style>

.danger {
  background-color: #ffdddd;
  border-left: 6px solid #f44336;
  /* padding: 10px; */
  /* margin-left: 15px; */
  /* float: right; */
  line-height: 40px;
  /* cursor: pointer;
  transition: 0.3s; */
  color: black;
  /* font-size: 14px; */
}

.alert{
    padding:10px;
}

/* img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 40%;
} */

</style>

<div>
    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>


    <?php $output = GridView::widget([
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
            'NOSIRI',
            'IDSAMPEL',
            'NAMAPEKERJA',
            'NOKP',
            [
                'attribute' => 'JANTINA',
                'value' => function($sampel) {
                    return OptionHandler::resolve('jantina', $sampel->JANTINA);
                }
            ],
            [
                'attribute' => 'TY2',
                'value' => function($sampel) {
                    return OptionHandler::resolve('ty2-fhc', $sampel->TY2);
                }
            ],
            [
                'attribute' => 'FHC',
                'value' => function($sampel) {
                    return OptionHandler::resolve('ty2-fhc', $sampel->FHC);
                }
            ],
            [
                'attribute' => 'KEPUTUSAN',
                'value' => function($sampel) {
                    return OptionHandler::resolve('keputusan', $sampel->KEPUTUSAN);
                }
            ],
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
                    'view' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['sampel-view', 'nosiri' => $model->NOSIRI, 'ID' => $model->ID]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['sampel', 'nosiri' => $model->NOSIRI, 'idsampel' => $model->ID]); //'nosiri', 'idsampel' are parameter in controller action -NOR06092022
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['sampel-delete'], [
                            'data-pjax' => '0',
                            'data-confirm' => 'Rekod akan dipadamkan. Maklumat sampel akan dikemaskini mengikut tarikh tindakan terkini. Teruskan.', //display onclick -NOR120922
                            'data-method' => 'post',
                            'data-params' => [
                                'nosiri' => $model->NOSIRI, 'idsampel' => $model->ID,
                            ],
                        ]);
                    },

                    // 'delete' => function ($url, $model) {
                    //     return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['sampel-delete'], [
                    //         'data-method' => 'post',
                    //         'data-params' => [
                    //             'nosiri' => $model->NOSIRI, 'ID' => $model->ID,
                    //         ],
                    //     ]);
                    // },
                ],
                'visibleButtons' => [
                    'view' =>   \Yii::$app->access->can('handswab-read'),
                    'update' => \Yii::$app->access->can('handswab-write'),
                    'delete' => \Yii::$app->access->can('handswab-write'),
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
    ]) ?>
        
    <h4 style="margin-top:0px;">Daftar Sampel Handswab</h4>
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:20px'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <div class="row">
        <!-- left side -->
        <div class="col-md-6">
            <?= $form->field($sampel, 'IDSAMPEL')->textInput(['disabled' => true]) ?>

            <?= $form->field($sampel, 'NOKP')->textInput(['maxlength' => true]) ?>

            <?= $form->field($sampel, 'TY2')->radioList(OptionHandler::render('ty2-fhc'), ['selector'=>'radio', 'inline'=>true]); ?>

            <?= $form->field($sampel, 'KEPUTUSAN')->radioList(OptionHandler::render('keputusan'), ['selector'=>'radio', 'inline'=>true]); ?>
        </div>

        <!-- right side -->
        <div class="col-md-6">
            <?= $form->field($sampel, 'NAMAPEKERJA')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($sampel, 'JANTINA')->radioList(OptionHandler::render('jantina'), ['selector'=>'radio', 'inline'=>true]); ?>

            <?= $form->field($sampel, 'FHC')->radioList(OptionHandler::render('ty2-fhc'), ['selector'=>'radio', 'inline'=>true]); ?>

            <?= $form->field($sampel, 'CATATAN')->textarea(['rows' => '3']) ?>

            <?= $form->field($sampel, 'imageFile')->fileInput() ?>

        </div>
            
        <!-- button simpan -->
        <div class="row">
            <div class="col-md-offset-10 col-md-2">
                <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                    'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1,
                    'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
                ]) ?>
            </div>
        </div>

    <?php ActiveForm::end() ?>

    <hr />
</div>


<?php

