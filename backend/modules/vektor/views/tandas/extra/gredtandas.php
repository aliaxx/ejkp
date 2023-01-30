<?php

// use backend\modules\makanan\models\Transkolam;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use backend\modules\vektor\models\Transtandas;

/**
 * @var View $this
 * @var Transkolam $gredtandas
 * @var ActiveForm $form
 */

$this->title = 'Penggredan Tandas';
$this->params['breadcrumbs'] = [
'Pencegahan Vektor',
['label' => 'Pemeriksaan Tandas', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/vektor/tandas/gredtandas', 'nosiri' => $model->NOSIRI]],
];

// var_dump($model->PTS_JENISTANDAS);
// exit();

// $model->PTS_JENISTANDAS = "1 dan 2";

$jenistandas_qs = str_replace(',', '%2C', $model->PTS_JENISTANDAS);

$jenistandas1 = explode(',', $model->PTS_JENISTANDAS);

$data['KATPREMIS'] = ['',''];
$source = Yii::$app->db->createCommand("SELECT * FROM TBPARAMETER_DETAIL WHERE KODJENIS = 1")->queryAll();
$data['KATPREMIS'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

if($gredtandas->transtandasrec ){
    // var_dump($gredtandas->KATPREMIS );
    $jumlahmarkah = $gredtandas->markahtandas['total'];
    $gred = $gredtandas->markahtandas['gred'];

}else {
    $jumlahmarkah = '';
    $gred = '';
}

?>

<div>
    <table align="right">
        <td>
            <?php $form = ActiveForm::begin(['method' => 'post', 'id' => 'viewForm','options'=> ['target' => '_blank']]); ?>
            <?= Html::submitButton('<i class="glyphicon glyphicon-print"></i> Cetak', [
                        'class' => 'btn btn-primary', 'id' => 'action_export-pdf', 'name' => 'action[export-pdf]',
                    ])  ?>
            <?php ActiveForm::end(); ?>
        </td>
    </table>
    <?php $form = ActiveForm::begin([
        'id' => 'transtandas-form', //ID TU REFER NAMA MODEL-TENGOK DEKAT F12 NAK CONFIRM (alia-080922)
    ]); ?>

    <div style="margin-top:61px;"></div>

    <?= \codetitan\widgets\ActionBar::widget([
        'permissions' => ['save2close' => Yii::$app->access->can('PTS-write')],
    ]) ?>

    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>
    <br>
    <!-- table title -->
    <?php if($gredtandas->transtandasrec) : ?>
        <div class="report-master-search">
            <div class="panel panel-default no-print">
                <div class="panel-heading">
                    <?= Yii::t('app', 'Keputusan Pemeriksaan') ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <?= $form->field($gredtandas, 'jumlahmarkah')->textInput(['value' => $jumlahmarkah])->label('Jumlah Markah')?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($gredtandas, 'gred')->textInput(['value' => $gred])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<div class="report-master-search">   
    
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Maklumat dan Pemarkahan* :') ?>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                        <?= $form->field($gredtandas, 'KATPREMIS')->widget(Select2::className(['value' => ($model->isNewRecord ? null : $gredtandas->KATPREMIS)]), [
                            'data' => $data['KATPREMIS'],
                            'options' => [
                                'placeholder' => '',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ]
                        ])->label('Kategori Premis') ?>
                </div>
            </div>
        </div>
        <!-- table title -->
        <!-- <h4>Pemarkahan* :  </h4> -->
        <div class="panel-body">
            <table id="tandasTable" class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <tr>
                            <th width=2%><?= Yii::t('app', 'No') ?></th>
                            <th width=10%><?= Yii::t('app', 'Perkara') ?></th>
                            <th width=18%><?= Yii::t('app', 'Komponen') ?></th>
                            <th width=10%><?= Yii::t('app', 'Markah') ?></th>

                            <?php for ($i=0; $i < count($jenistandas1); $i++): ?>
                                <?php if ($jenistandas1[$i]==1): ?>
                                <th width=10%><?= Yii::t('app', 'Lelaki') ?></th>
                                <?php elseif($jenistandas1[$i]==2): ?>
                                <th width=10%><?= Yii::t('app', 'Wanita') ?></th>
                                <?php elseif($jenistandas1[$i]==3): ?>
                                <th width=10%><?= Yii::t('app', 'OKU') ?></th>
                                <?php elseif($jenistandas1[$i]==4): ?>
                                <th width=10%><?= Yii::t('app', 'Unisex') ?></th>
                                <?php elseif($jenistandas1[$i]==5): ?>
                                <th width=10%><?= Yii::t('app', 'Kanak-Kanak') ?></th>
                                <?php endif; ?>
                                
                            <?php endfor; ?> 
                            <th width=12%><?= Yii::t('app', 'Jumlah Markah') ?></th>
                            
                            
                        </tr>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>        
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-8 action-buttons" style="margin-left:480px;">
        <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-saved"></i> Simpan', [
            'id' => 'action_save2close', 'class' => 'btn btn-primary', 'name' => 'action[save2close]', 'value' => 1, 
            'title' => 'Simpan', 'aria-label' => 'Simpan', 'data-pjax' => 0,
        ]) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerCss("
#tandasTable > thead > tr > th {text-align:center}
#tandasTable > tbody > tr > td {text-align:center}
#tandasTable > tfoot > tr > td {font-size:16px;font-weight:bold;background-color:#CCC}
#tandasTable > tfoot > tr > td:first-child {text-align:right}
#tandasTable > tfoot > tr > td:nth-child(2) {text-align:center}
.form-horizontal .form-group {
    margin-right: 0;
    margin-left: 0;
}"
);

$this->registerJs("

//var itemCounter = 1;
var itemCounter = 0;

//function getItemForm(initialize)
function getItemForm(initialize, checkall, pts_jenistandas)
{
    // alert(initialize);
    // alert(pts_jenistandas);
    let urlItemForm = '" . Url::to(['/vektor/tandas/get-item-form']) . "';
    //$.get(urlItemForm + '?counter=' + itemCounter + '&initialize=' + initialize, function (data) {
        $.get(urlItemForm + '?counter=' + itemCounter + '&initialize=' + initialize  + '&checkall=' + checkall + '&pts_jenistandas=' + pts_jenistandas, function (data) {
            $('#tandasTable tbody').append(data);
    });
}", View::POS_END);

if (!$gredtandas->transtandasrec) {
    $this->registerJs("
    $(document).ready(function () {
        getItemForm(0, true, '" . $model->PTS_JENISTANDAS ."');
        itemCounter++;
    });
    ", View::POS_END);
} else {
    $this->registerJs("
    $(document).ready(function () {
        itemCounter = " . (count($gredtandas->transtandasrec) + 1) . ";


        getItemForm('" . $gredtandas->NOSIRI . "', false, '" . $model->PTS_JENISTANDAS. "'); 
    });
    ", View::POS_END);
}


