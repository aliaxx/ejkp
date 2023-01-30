<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use kartik\widgets\DateTimePicker;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use common\utilities\DateTimeHelper;
use codetitan\widgets\LookupInput;

use common\models\Pengguna;
use backend\modules\peniaga\utilities\OptionHandler;


/* @var $this yii\web\View */
/* @var $model backend\modules\peniaga\models\penggredanpremis */
/* @var $form yii\widgets\ActiveForm */


$model->IDMODULE='APB';
$sources= Yii::$app->db->createCommand("SELECT PRGN FROM TBMODULE WHERE ID ='$model->IDMODULE' ")->queryOne();
// $source = Yii::$app->db->createCommand("SELECT * FROM TBLOKASI")->queryAll();
$model->prgnidmodule = $sources['PRGN'];

//display data daripada TBMARKAH.
$data['GRED'] = ['',''];
$source = Yii::$app->db->createCommand("SELECT GRED FROM TBMARKAH ")->queryAll();

$data['GRED'] = ArrayHelper::map($source, 'GRED', 'GRED');


// var_dump($lawatan);
// exit;
// $sources= Yii::$app->db->createCommand("SELECT NOLESEN,NOSSM FROM TBLAWATAN_PEMILIK WHERE NOSIRI= '$lawatan->NOSIRI' ")->queryOne();
// // $source = Yii::$app->db->createCommand("SELECT * FROM TBLOKASI")->queryAll();
// $model->NOLESEN = $sources['NOLESEN'];
// $model->NOSSM = $sources['NOSSM'];

// var_dump($model->NOSSM);
// exit;



// $lawatan = $this->findModel($id);
        // var_dump($lawatan->pemilik0->NOLESEN);
        // exit;

if ($model->isNewRecord) {
    $model->setNoSiri('APB'); //set and display nosiri -NOR27092022 

    $sources= Yii::$app->db->createCommand("SELECT NOLESEN,NOSSM FROM TBLAWATAN_PEMILIK WHERE NOSIRI= '$lawatan->NOSIRI' ")->queryOne();
    // $source = Yii::$app->db->createCommand("SELECT * FROM TBLOKASI")->queryAll();
    $model->NOLESEN = $sources['NOLESEN'];
    $model->NOSSM = $sources['NOSSM'];
}

?>


<?php $form = ActiveForm::begin([
        'id' => 'lawatanmain-form', //ID TU REFER NAMA MODEL-TENGOK DEKAT F12 NAK CONFIRM (alia-080922)
    ]); ?>

    <?= \codetitan\widgets\ActionBar::widget([
        'permissions' => ['save2close' => Yii::$app->access->can('APB-write')],
    ]) ?>
              
<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Maklumat Lawatan Premis') ?>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">  
                    <!-- lAWATAN PREMIS Forms -->
                   
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOSIRI')->textInput(['disabled' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'prgnidmodule')->textInput(['disabled' => true]) ?>
                            <?= $form->field($model, 'IDMODULE')->textInput(['type' => 'hidden'])->label(false) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'TAHUN')->textInput()->widget(DateTimePicker::className(), [
                                'name' => 'TAHUN',
                                'readonly' => true,
                                'options' => ['class' => 'custom-datetime-field'],
                                'pluginOptions' => [
                                    // 'minuzonamep' => 1,
                                    // 'todayHighlight' => true,
                                    // 'format' => 'yyyy',
                                    // 'autoclose' => true,
                                    // 'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                    'autoclose' => true,
                                    'startView'=>'year',
                                    'minViewMode'=>'year',
                                    'format' => 'yyyy'
                                ]
                            ]);?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'GRED')->widget(Select2::className(), [
                                'data' => $data['GRED'],
                                'options' => [
                                    'placeholder' => '',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOLESEN')->textInput(['disabled' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOSSM')->textInput(['disabled' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'CATATAN') ?>
                        </div>
                    </div>
<!-- Submit buttons -->
<div class="row">
    <div class="col-md-12">
        <span class="pull-right">              
            <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['index'], [
                'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]',
                'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
            ]) ?>

            <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
                'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1,
                'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
            ]) ?>
        </span>
    </div>
</div>
<?php ActiveForm::end(); ?>       
</div>                  
                    


