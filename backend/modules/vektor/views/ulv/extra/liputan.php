<?php

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

$this->title = 'Liputan Semburan';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
['label' => 'Semburan Kabus Mesin (ULV)', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/vektor/ulv/lipatan', 'nosiri' => $model->NOSIRI]],
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

<div style="margin-top:61px;"></div>

<div>
    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>

<br>
<div>
    <!-- <h4 style="margin-top:0px;">Sasaran Jumlah Jenis Premis Dalam Linkungan 200M</h4> -->
    <?php $form = ActiveForm::begin([
        'id' => 'sasaranulv-form', //id is important to define form class for each button -NOR11102022
        'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off', 'style' => 'padding-top:20px'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-3 control-label'],
        ],
    ]); ?>


    <!-- sasaran semburan forms-->
    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Sebab Premis Tidak Disembur Dan Tidak Disembur Lengkap') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">  
                        <div class="row">
                            <div class="col-md-12">
                                <?= $this->render('_liputan', [
                                    'liputan' => $liputan,
                                    // 'model' => $model,
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
    <!-- </div> -->

    <?php ActiveForm::end() ?>
    <!-- <hr /> -->
</div>


<?php

