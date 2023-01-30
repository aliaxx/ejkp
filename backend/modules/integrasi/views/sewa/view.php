<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii2assets\printthis\PrintThis;

use common\utilities\OptionHandler;
use common\utilities\DateTimeHelper;

use backend\modules\integrasi\models\Sewa;


/* @var $this yii\web\View */
/* @var $model backend\modules\integrasi\models\Sewa */

$this->title = 'Paparan Maklumat';
$this->params['breadcrumbs'] = [
    'Integrasi',
    ['label' => 'Integrasi Sewa', 'url' => ['index']],
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
        'attributes' => [
            'ACCOUNT_NUMBER',
            'NAME',
            'ADDRESS_2',
            'ADDRESS_POSTCODE',
            'LOCATION_ID',
            'RENT_CATEGORY',
            'ASSET_ADDRESS_1',
            'ASSET_ADDRESS_3',
            'ASSET_ADDRESS_LAT',
            'RENT_AMOUNT',
        ],
        ])?>
    </div>

    <!-- Maklumat Permohonan Lesen on right side -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                'LICENSE_NUMBER' , 
                'ADDRESS_1',   
                'ADDRESS_3', 
                'LOT_NO',
                'LOCATION_NAME',
                'SALES_TYPE', 
                'ASSET_ADDRESS_2',   
                'ASSET_ADDRESS_POSTCODE', 
                'ASSET_ADDRESS_LONG',
                'OUTSTANDING_RENT_AMOUNT',
            ],
        ]) ?>
    </div>

<!-- End Maklumat  Permohonan Lesen -->


<!-- Maklumat Penjaja -->
<table style="height:40px; width:100%;">
    <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Penjaja</h5></th></tr>
</table>

    <div style="float:left; width:50%;">
    <?= DetailView::widget([
        'model' => $model,
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

    <!-- Maklumat Permohonan Lesen on right side -->
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
    