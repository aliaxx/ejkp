<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
// use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;

use kartik\widgets\DateTimePicker;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use common\utilities\DateTimeHelper;
use codetitan\widgets\LookupInput;

use backend\modules\makanan\utilities\OptionHandler;
use backend\modules\penyelenggaraan\models\ParamDetail;
// var_dump();
// exit();

$this->title = $model->NOSIRI;
$this->params['breadcrumbs'] = [
    ['label' => 'PTP', 'url' => ['index']],
    $this->title,
    'Bekas Diperiksa',
    
];

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

 /* .div1{
         text-align:left; 
         float: left;
         width:50%;
   } */

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

<div>
    <!-- <h4 style="margin-top:0px;">Jenis-Jenis Bekas Diperiksa</h4> -->
    <?php $form = ActiveForm::begin([
        // 'id' => 'bekasptp-form', 
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:20px'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-3 control-label'],
        ],
    ]); ?>


    <!-- bekas semburan forms-->
    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Jenis-Jenis Bekas Diperiksa') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">  
                        <div class="row">
                            <div class="col-md-12">
                                <?= $this->render('_bekas-form', [
                                    'bekas' => $bekas,
                                    'model' => $model,
                                    'form' => $form,
                                    'dataProvider' => $dataProvider,
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
    <!-- </div> -->

    <?php ActiveForm::end() ?>
    <!-- <hr /> -->

    <!-- <div class="row"> -->
    <div class="form-group">
        <div class="col-md-6">
            <!-- <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'PTP_BILBEKASMUSNAH',
                    ],
                ])
            ?>   -->
        </div>
        <!-- <div class="col-lg-offset-1 col-lg-5 col-md-6 action-buttons"> -->
            <!-- <?= Html::a('Kira Pencapaian', ['kira', 'nosiri' => $bekas->NOSIRI], ['class' => 'btn btn-success', 
            'style'=>'width:200px'])?> -->
        <!-- </div> -->
    </div>
<!-- <div> -->

</div>


<?php

