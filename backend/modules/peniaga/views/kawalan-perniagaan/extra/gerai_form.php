<?php

use backend\modules\peniaga\models\KawalanPerniagaan;
use backend\modules\peniaga\models\KawalanPerniagaanSearch;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\utilities\DateTimeHelper;


use kartik\widgets\DepDrop;

/**
 * @var View $this
 * @var Datakes $model
 * @var DatakesApp $kesApp
 * @var ActiveDataProvider $dataProvider
 */

$this->title = 'Gerai/Petak';
$this->params['breadcrumbs'] = [
    ['label' => 'Pemantauan Gerai', 'url' => ['index']],
    $model->nokes,
    $this->title,
];

if (!$kesApp->isNewRecord) {

    $kodjenistuntutan = JenisTuntutan::find()->where(['kodkattuntutan' => '1', 'status' => 1])->all();
    $data['kodjenistuntutan'] =  yii\helpers\ArrayHelper::map($kodjenistuntutan, 'kodjenistuntutan', function($model) {
        return $model->prgn;
    });
}
?>


<div>
    <!-- -->

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:20px'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    
<h4 style="margin-top:0px;">Daftar Kes Tribunal Tuntutan Pengguna Malaysia (TTPM)</h4>
    <div class="row">

            <?= $form->field($kesApp, 'nottpm')->textInput(['maxlength' => true]) ?>

            <?= $form->field($kesApp, 'kodlokasi')->widget(Select2::className(), [
                'data' => $data['kodlokasi'],
                'options' => [
                    'placeholder' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]) ?>

            <?= $form->field($kesApp, 'trkhaward')->widget(DatePicker::className(), [
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]
            ])  ?>

            <?= $form->field($kesApp, 'trkhserahan')->widget(DatePicker::className(), [
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]

 

            ])  
            

            ?>

            <?php

            // var_dump($trkhkesalahan);
            // exit();
            ?>
            <?= $form->field($kesApp, 'trkhaduan')->widget(DatePicker::className(), [
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]
            ])  ?>
            
            <?= $form->field($kesApp, 'pengadu')->textInput(['maxlength' => true]) ?>

            <?= $form->field($kesApp, 'penentang')->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($kesApp, 'kodborang')->widget(Select2::className(), [
                'data' => $data['kodborang'],
                'options' => [
                    'placeholder' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]) ?>
            

            <?= $form->field($kesApp, 'prgnaward')->textarea(['maxlength' => true, 'style' => 'height:80px']) ?>

            <?= $form->field($kesApp, 'nilaiaward')->textInput(['maxlength' => true, 'style' => 'width:150px']) ?>

            <?= $form->field($kesApp, 'kodkattuntutan')->widget(Select2::classname(), [
                    'data' => $data['kodkattuntutan'],
                    'options' => ['placeholder' => '', 'id' => 'kodkattuntutan'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>

                    
            <?= $form->field($kesApp, 'kodjenistuntutan')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'data' => $data['kodjenistuntutan'],
                    'options' => ['id'=>'kodjenistuntutan'],
                    'pluginOptions'=>[
                        'depends'=>['kodkattuntutan'],
                        'placeholder'=>'',
                        'url'=>Url::to(['/datakes/pengurusan/get-jenis-tuntutan'])
                    ]
                    ])?>

            <?= $form->field($kesApp, 'catatan')->textarea(['maxlength' => true, 'style' => 'height:80px']) ?>
            
    </div>

    <?php if(($kesApp->nottpm <>'')&& ($kesApp->nottpm ==null)): ?>   
        <div class="row">
            <div class="col-md-offset-10 col-md-2">
                <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                    'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1,
                    'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
                ]) ?>
            </div>
        </div>
    <?php endif ?>

    <?php ActiveForm::end() ?>

    <hr />

   
</div>
<?php
$this->registerJs("
// // PHP program to add days to date
  
// // Declare a date 
// // trkhkesalahan = trkhaward; 
// var trkhkesalahan = $('#datakesapp-trkhaward')val(data.results.trkhaward);
// // $('#kompaun-namapenerima').val(data.results.namapenerima);
  
// // Add days to date and display it 
// echo date('Y-m-d', strtotime(trkhkesalahan. ' + 10 days')); 
", \yii\web\View::POS_END);
