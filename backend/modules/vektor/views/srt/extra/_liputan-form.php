<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use backend\modules\vektor\models\TransLiputansrt;
use backend\modules\vektor\models\TransLiputansrt;

/* @var $this yii\web\View */
/* @var $model app\models\Countries */
/* @var $form yii\widgets\ActiveForm */

// $liputan = TransLiputansrt::findOne(['NOSIRI' => $model->NOSIRI]);

?>

<style>
table, th, td {
  border: 1px solid lightslategray;
  border-collapse: separate;
}

/* tr {
  border: 1px solid black;
  border-collapse: separate;
} */

th {
  background-color: #B0C4DE;
}  

td:nth-child(1), td:nth-child(2){
  background-color: #DCDCDC;
}  

/* th:nth-child(even),td:nth-child(even) {
  background-color: rgba(150, 212, 212, 0.4);
}  */
</style>

<div class="row">
    <!-- <div class="col-md-offset-12 col-md-12"> col-md-offset-12 cause table to drag to the right -NOR30092022 -->
    <div class="col-md-12">
        <!-- <table class="table table-condensed"> -->
        <table id="liputanTable" class="table table-condensed">
            <thead style="color:#000000">
                <tr>
                    <tr>
                        <th style="width:20%; text-align:center"><?= Yii::t('app', 'Jenis Pengabutan') ?></th>
                        <th style="width:20%; text-align:center"><?= Yii::t('app', 'Pengabutan') ?></th>
                        <th style="width:20%; text-align:center"><?= Yii::t('app', 'Jumlah Premis') ?></th>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Pemeriksaan Tidak Lengkap') ?></td>
                        <td><?= Yii::t('app', 'Dalam Rumah Sahaja') ?></td>
                        <td>
                            <?= $form->field($model, 'V_TLENGKAPDALAM')->textInput([
                                'type' => 'number', 
                                'min' => 0,
                                ])->label(false)?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', '') ?></td>
                        <td><?= Yii::t('app', 'Luar Rumah Sahaja') ?></td>
                        <td>
                            <?= $form->field($model, 'V_TLENGKAPLUAR')->textInput([
                                'type' => 'number', 
                                'min' => 0
                                ])->label(false)?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Pemeriksaan  Lengkap') ?></td>
                        <td><?= Yii::t('app', 'Dalam dan Luar Rumah') ?></td>
                        <td>
                            <?= $form->field($model, 'V_LENGKAP')->textInput([
                                'type' => 'number', 
                                'min' => 0
                                ])->label(false)?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Tidak Diperiksa') ?></td>
                        <td><?= Yii::t('app', '') ?></td>
                        <td>
                            <?= $form->field($model, 'V_TPERIKSA')->textInput([
                                'type' => 'number', 
                                'min' => 0
                                ])->label(false)?>
                        </td>
                    </tr>
                </tr>
            </thead>
            <!-- <tbody> -->
                <!-- tbody use to display record -NOR04102022-->
            <!-- </tbody>             -->
        </table>
    </div>
</div>

<?php
$this->registerCss("
#liputanTable > thead > tr > th {text-align:center;height:100%}
#liputanTable > tbody > tr > td {text-align:center}
#liputanTable > tfoot > tr > td {font-size:16px;font-weight:bold;background-color:#CCC}
#liputanTable > tfoot > tr > td:first-child {text-align:right}
#liputanTable > tfoot > tr > td:nth-child(2) {text-align:center}
.form-horizontal .form-group {
    margin-right: 0;
    margin-left: 0;
}
");
