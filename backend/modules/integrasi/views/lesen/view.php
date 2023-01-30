<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii2assets\printthis\PrintThis;

use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;
use backend\modules\integrasi\models\LesenMaster;


/* @var $this yii\web\View */
/* @var $model backend\modules\integrasi\models\LesenMaster */

$this->title = 'Paparan Maklumat';
$this->params['breadcrumbs'] = [
    'Integrasi',
    ['label' => 'Integrasi Lesen', 'url' => ['index']],
    $this->title,
];
?>

<div>
    <table align="right">
        <td>
            <?php $form = ActiveForm::begin(); ?>

                <?= Html::button('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', array(
                                    'name' => 'btnBack',
                                    'class' => 'btn btn-danger',
                                    'style' => 'width-left: 95%;',
                                    'onclick' => "history.go(-1)",
                )) ?>
            <?php ActiveForm::end(); ?>
        </td>

        <td>&nbsp;</td>

        <td>
            <!-- <?php $form = ActiveForm::begin(['method' => 'post', 'id' => 'viewForm','options'=> ['target' => '_blank']]); ?>
            <?= Html::submitButton('<i class="glyphicon glyphicon-print"></i> Cetak', [
                        'class' => 'btn btn-primary', 'id' => 'action_export-pdf', 'name' => 'action[export-pdf]',
                    ])  ?> -->


            <?php ActiveForm::end(); ?>
        </td>
    </table>    

<br><br>

<!-- Maklumat Permohonan Lesen -->
<table style="height:40px; width:100%;">
    <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Permohonan Lesen</h5></th></tr>
</table>

    <!-- Maklumat Permohonan Lesen on left side -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                'NO_AKAUN',
                'NO_KP_PEMOHON',
                'NAMA_SYARIKAT',
                'TARIKH_PERMOHONAN',
                'ALAMAT_PREMIS1',
                'ALAMAT_PREMIS3',
                [
                    'attribute' => 'STATUS_PERMOHONAN',
                        'value' => OptionHandler::resolve('status-lesen', $model->STATUS_PERMOHONAN),
                ],
                'KUMPULAN_LESEN',
                'KATEGORI_LESEN',
            ],
        ])?>
    </div>


    <!-- Maklumat Permohonan Lesen on right side -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                'ID_PERMOHONAN', 
                'NAMA_PEMOHON',    
                'NO_DFT_SYKT',    
                'JENIS_PREMIS',
                'ALAMAT_PREMIS2',   
                'POSKOD',
                'TARIKH_BATAL_TANGGUH',
                'KETERANGAN_KUMPULAN',
                'KETERANGAN_KATEGORI',
            ],
        ])?>
    </div>



<!-- Maklumat Penjaja -->
<table style="height:40px; width:100%;">
    <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Penjaja</h5></th></tr>
</table>

    <!-- Maklumat Penjaja on left side -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'JENIS_LESEN',
                    'value' => function ($model){
                        return (!empty($model->penjaja->JENIS_LESEN))? $model->penjaja->JENIS_LESEN: null;
                    }
                ],
                [
                    'attribute' => 'LOKASI_MENJAJA',
                    'value' => function ($model){
                        return (!empty($model->penjaja->LOKASI_MENJAJA))? $model->penjaja->LOKASI_MENJAJA: null;
                    }
                ],
                [
                    'attribute' => 'KAWASAN',
                    'value' => function ($model){
                        return (!empty($model->penjaja->KAWASAN))? $model->penjaja->KAWASAN: null;
                    }
                ],
                [
                    'attribute' => 'JENIS_JAJAAN',
                    'value' => function ($model){
                        return (!empty($model->penjaja->JENIS_JAJAAN))? $model->penjaja->JENIS_JAJAAN: null;
                    }
                ],
            ],
        ]) ?>
    </div>

    <!-- Maklumat Penjaja on right side -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'AMAUN_LESEN',
                    'value' => function ($model){
                        return (!empty($model->penjaja->AMAUN_LESEN))? $model->penjaja->AMAUN_LESEN: null;
                    }
                ],
                [
                    'attribute' => 'JENIS_JUALAN',
                    'value' => function ($model){
                        return (!empty($model->penjaja->JENIS_JUALAN))? $model->penjaja->JENIS_JUALAN: null;
                    }
                ],
                [
                    'attribute' => 'ID_KAWASAN',
                    'value' => function ($model){
                        return (!empty($model->penjaja->ID_KAWASAN))? $model->penjaja->ID_KAWASAN: null;
                    }
                ],

            ],
        ]) ?>
    </div>
 
    