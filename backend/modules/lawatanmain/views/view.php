<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;


use backend\modules\makanan\utilities\OptionHandler;
use backend\modules\lawatanmain\models\LawatanPasukan;

/* @var $this yii\web\View */
/* @var $model backend\modules\peniaga\models\KawalanPerniagaan */

$this->title = $title;
$this->params['breadcrumbs'] = $breadCrumbs;

\yii\web\YiiAsset::register($this);

if ($model->pasukanAhlis) {
    foreach ($model->pasukanAhlis as $ahli) {
        if ($ahli->JENISPENGGUNA == LawatanPasukan::STATUS_AHLI) {
            $model->ahlipasukan[] = $ahli->pengguna0->NAMA;
        }
    }
}

// var_dump($model->lokasi0->NAME);
// exit();

?>

    <?php $form = ActiveForm::begin(); ?>
        <div class="action-buttons">
            <?= Html::submitButton('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', [
                'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
                'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
            ]) ?>
            </div>
    
<!-- ================================================== display tabmenu based on idmodule ======================================================= -->
    <!-- Mutu Makanan -->
    <?php if ($model->IDMODULE == 'SMM'): ?>
          <?=   $this->render('/sampel/extra/_tab', [
            'model' => $model
          ]) ?>
    <?php elseif ($model->IDMODULE == 'SDR'): ?>
        <?=   $this->render('/sitaan/extra/_tab', [
            'model' => $model
        ]) ?>
    <?php elseif ($model->IDMODULE == 'HSW'): ?>
        <?=   $this->render('/handswab/extra/_tab', [
        'model' => $model
        ]) ?>
    <?php elseif ($model->IDMODULE == 'PKK'): ?>
        <?=   $this->render('/kolam/extra/_tab', [
            'model' => $model
        ]) ?>

    <!-- Peniaga Kecil & Penjaja -->
    <?php elseif ($model->IDMODULE == 'KPN'): ?>
        <?=   $this->render('/kawalan-perniagaan/extra/_tab', [
            'model' => $model
        ]) ?>

    <!-- Premis Makanan -->
    <?php elseif ($model->IDMODULE == 'PPM'): ?>
    <?=   $this->render('/penggredan-premis/extra/_tab', [
        'model' => $model
    ]) ?>

    <!-- Penecegahan Vektor -->
    <?php elseif ($model->IDMODULE == 'SRT'): ?>
    <?=   $this->render('/srt/extra/_tab', [
        'model' => $model
    ]) ?>
    <?php elseif ($model->IDMODULE == 'ULV'): ?>
    <?=   $this->render('/ulv/extra/_tab', [
        'model' => $model
    ]) ?>
    <?php elseif ($model->IDMODULE == 'PTP'): ?>
    <?=   $this->render('/ptp/extra/_tab', [
        'model' => $model
    ]) ?>
    <?php elseif ($model->IDMODULE == 'LVC'): ?>
    <?=   $this->render('/lvc/extra/_tab', [
        'model' => $model
    ]) ?>
    <?php elseif ($model->IDMODULE == 'PTS'): ?>
    <?=   $this->render('/tandas/extra/_tab', [
        'model' => $model
    ]) ?>
    <?php endif ?>
    <?php ActiveForm::end(); ?>

<!-- ======================================================== end for tabmenu =================================================================== -->


<!-- ===================================== Display data Maklumat Lawatan Premis for Sampel Makanan ====================================================== -->
<?php if ($model->IDMODULE == 'SMM'): ?>
<br><br>
<!-- Maklumat Lawatan Premis on left side -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Lawatan Premis</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Mula Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHMULA)),
                ],
                'NOSIRI',
                [
                    'attribute' => 'JENISPREMIS',
                    'value' => OptionHandler::resolve('jenis-premis', $model->JENISPREMIS),
                ],  
                [
                    'attribute' => 'PRGNLOKASI_AM',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI_AM))? $model->PRGNLOKASI_AM: null;
                    }
                ],
                [
                    'attribute' => 'IDDUN',
                    'value' => function ($model){
                        return (!empty($model->dun0->PRGNDUN))? $model->dun0->PRGNDUN: null;
                    }
                ],
                [
                    'attribute' => 'SMM_ID_JENISSAMPEL',
                    'value' => function ($model){
                        return (!empty($model->jenis->PRGN))? $model->jenis->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'LATITUD',
                    'value' => function ($model){
                        return (!empty($model->LATITUD))? $model->LATITUD: null;
                    }
                ],
                'CATATAN', 
                [
                    'attribute' => 'PGNDAFTAR',
                    'value' => $model->createdByUser->NAMA,
                ],
                [
                    'attribute' => 'PGNAKHIR',
                    'value' => $model->createdByUser->NAMA,
                ],
                
            ],            
        ])?>
    </div>
    
<!-- Maklumat Lawatan Premis on right side -->
    <!-- <div style="float:left; margin-left:12px; width:50%;"> -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Tamat Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHTAMAT)),
                ],
                [
                    'attribute' => 'IDMODULE',
                    'value' => function ($model){
                        return (!empty($model->idmodule->PRGN))? $model->idmodule->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'ID_TUJUAN',
                    'value' => function ($model){
                        return (!empty($model->tujuan0->PRGN))? $model->tujuan0->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'PRGNLOKASI',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI))? $model->PRGNLOKASI: null;
                    }
                ],
                [
                    'attribute' => 'NOADUAN',
                    'value' => function ($model){
                        return (!empty($model->NOADUAN))? $model->NOADUAN: null;
                    }
                ],
                [ //nor30112022
                    'attribute' => 'SMM_MAKMAL',
                    'value' => function ($model){
                        return (!empty($model->makmal->PRGN))? $model->makmal->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'LONGITUD',
                    'value' => function ($model){
                        return (!empty($model->LONGITUD))? $model->LONGITUD: null;
                    }
                ],              
                'TRKHDAFTAR:datetime',
                'TRKHAKHIR:datetime',
            ],
        ]) ?>
    </div>
<!-- End Maklumat Lawatan Premis -->
<?php endif ?>
<!-- ========================================= End display Maklumat Lawatan Premis for Sampel Makanan ================================================== -->



<!-- ========================================== Display data Maklumat Lawatan Premis for Sitaan ======================================================== -->
<?php if ($model->IDMODULE == 'SDR'): ?>
<br><br>
<!-- Maklumat Lawatan Premis on left side -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Lawatan Premis</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Mula Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHMULA)),
                ],
                'NOSIRI',
                [
                    'attribute' => 'JENISPREMIS',
                    'value' => OptionHandler::resolve('jenis-premis', $model->JENISPREMIS),
                ],  
                [
                    'attribute' => 'PRGNLOKASI_AM',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI_AM))? $model->PRGNLOKASI_AM: null;
                    }
                ],
                [
                    'attribute' => 'IDDUN',
                    'value' => function ($model){
                        return (!empty($model->dun0->PRGNDUN))? $model->dun0->PRGNDUN: null;
                    }
                ],
                [
                    'attribute' => 'SDR_ID_STOR',
                    'value' => function ($model){
                        return (!empty($model->stor->PRGN))? $model->stor->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'LATITUD',
                    'value' => function ($model){
                        return (!empty($model->LATITUD))? $model->LATITUD: null;
                    }
                ],
                [
                    'attribute' => 'PGNDAFTAR',
                    'value' => $model->createdByUser->NAMA,
                ],
                [
                    'attribute' => 'PGNAKHIR',
                    'value' => $model->createdByUser->NAMA,
                ],
                
            ],            
        ])?>
    </div>
    
<!-- Maklumat Lawatan Premis on right side -->
    <!-- <div style="float:left; margin-left:12px; width:50%;"> -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Tamat Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHTAMAT)),
                ],
                [
                    'attribute' => 'IDMODULE',
                    'value' => function ($model){
                        return (!empty($model->idmodule->PRGN))? $model->idmodule->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'ID_TUJUAN',
                    'value' => function ($model){
                        return (!empty($model->tujuan0->PRGN))? $model->tujuan0->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'PRGNLOKASI',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI))? $model->PRGNLOKASI: null;
                    }
                ],
                [
                    'attribute' => 'NOADUAN',
                    'value' => function ($model){
                        return (!empty($model->NOADUAN))? $model->NOADUAN: null;
                    }
                ],
                'CATATAN',
                [
                    'attribute' => 'LONGITUD',
                    'value' => function ($model){
                        return (!empty($model->LONGITUD))? $model->LONGITUD: null;
                    }
                ],                
                'TRKHDAFTAR:datetime',
                'TRKHAKHIR:datetime',
            ],
        ]) ?>
    </div>
<!-- End Maklumat Lawatan Premis -->
<?php endif ?>
<!-- ============================================== End display Maklumat Lawatan Premis for Sitaan ===================================================== -->


<!-- ======================================== Display data Maklumat Lawatan Premis for Handswab ======================================================== -->
<?php if ($model->IDMODULE == 'HSW'): ?>
<br><br>
<!-- Maklumat Lawatan Premis on left side -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Lawatan Premis</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Mula Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHMULA)),
                ],
                'NOSIRI',
                [
                    'attribute' => 'JENISPREMIS',
                    'value' => OptionHandler::resolve('jenis-premis', $model->JENISPREMIS),
                ],  
                [
                    'attribute' => 'PRGNLOKASI_AM',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI_AM))? $model->PRGNLOKASI_AM: null;
                    }
                ],
                [
                    'attribute' => 'IDDUN',
                    'value' => function ($model){
                        return (!empty($model->dun0->PRGNDUN))? $model->dun0->PRGNDUN: null;
                    }
                ],
                [
                    'attribute' => 'LATITUD',
                    'value' => function ($model){
                        return (!empty($model->LATITUD))? $model->LATITUD: null;
                    }
                ],
                'CATATAN',
                [
                    'attribute' => 'PGNDAFTAR',
                    'value' => $model->createdByUser->NAMA,
                ],
                [
                    'attribute' => 'PGNAKHIR',
                    'value' => $model->createdByUser->NAMA,
                ],
                
            ],            
        ])?>
    </div>
    
<!-- Maklumat Lawatan Premis on right side -->
    <!-- <div style="float:left; margin-left:12px; width:50%;"> -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Tamat Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHTAMAT)),
                ],
                [
                    'attribute' => 'IDMODULE',
                    'value' => function ($model){
                        return (!empty($model->idmodule->PRGN))? $model->idmodule->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'ID_TUJUAN',
                    'value' => function ($model){
                        return (!empty($model->tujuan0->PRGN))? $model->tujuan0->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'PRGNLOKASI',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI))? $model->PRGNLOKASI: null;
                    }
                ],
                [
                    'attribute' => 'NOADUAN',
                    'value' => function ($model){
                        return (!empty($model->NOADUAN))? $model->NOADUAN: null;
                    }
                ],
                [
                    'attribute' => 'LONGITUD',
                    'value' => function ($model){
                        return (!empty($model->LONGITUD))? $model->LONGITUD: null;
                    }
                ],                
                'TRKHDAFTAR:datetime',
                'TRKHAKHIR:datetime',
            ],
        ]) ?>
    </div>
<!-- End Maklumat Lawatan Premis -->
<?php endif ?>
<!-- ============================================ End display Maklumat Lawatan Premis for Handswab ==================================================== -->


<!-- ========================================== Display data Maklumat Lawatan Premis for Kolam ======================================================== -->
<?php if ($model->IDMODULE == 'PKK'): ?>
<br><br>
<!-- Maklumat Lawatan Premis on left side -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Lawatan Premis</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Mula Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHMULA)),
                ],
                'NOSIRI',
                [
                    'attribute' => 'JENISPREMIS',
                    'value' => OptionHandler::resolve('jenis-premis', $model->JENISPREMIS),
                ],  
                [
                    'attribute' => 'PRGNLOKASI_AM',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI_AM))? $model->PRGNLOKASI_AM: null;
                    }
                ],
                [
                    'attribute' => 'IDDUN',
                    'value' => function ($model){
                        return (!empty($model->dun0->PRGNDUN))? $model->dun0->PRGNDUN: null;
                    }
                ],
                [
                    'attribute' => 'LATITUD',
                    'value' => function ($model){
                        return (!empty($model->LATITUD))? $model->LATITUD: null;
                    }
                ],
                [
                    'attribute' => 'PGNDAFTAR',
                    'value' => $model->createdByUser->NAMA,
                ],
                [
                    'attribute' => 'PGNAKHIR',
                    'value' => $model->createdByUser->NAMA,
                ],
                
            ],            
        ])?>
    </div>
    
<!-- Maklumat Lawatan Premis on right side -->
    <!-- <div style="float:left; margin-left:12px; width:50%;"> -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Tamat Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHTAMAT)),
                ],
                [
                    'attribute' => 'IDMODULE',
                    'value' => function ($model){
                        return (!empty($model->idmodule->PRGN))? $model->idmodule->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'ID_TUJUAN',
                    'value' => function ($model){
                        return (!empty($model->tujuan0->PRGN))? $model->tujuan0->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'PRGNLOKASI',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI))? $model->PRGNLOKASI: null;
                    }
                ],
                [
                    'attribute' => 'NOADUAN',
                    'value' => function ($model){
                        return (!empty($model->NOADUAN))? $model->NOADUAN: null;
                    }
                ],
                [
                    'attribute' => 'LONGITUD',
                    'value' => function ($model){
                        return (!empty($model->LONGITUD))? $model->LONGITUD: null;
                    }
                ],                
                'TRKHDAFTAR:datetime',
                'TRKHAKHIR:datetime',
            ],
        ]) ?>
    </div>
<!-- End Maklumat Lawatan Premis -->
<?php endif ?>
<!-- =================================================== End display Maklumat Lawatan Premis for Kolam ================================================ -->



<!-- ======================================= Display data Maklumat Lawatan Premis for Pencegahan Vektor =============================================== -->
<?php if ($model->IDMODULE == 'SRT' || $model->IDMODULE == 'ULV' || $model->IDMODULE == 'PTP' || $model->IDMODULE == 'LVC'): ?>
<br><br>
<!-- Maklumat Lawatan Premis on left side -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Lawatan Premis</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Mula Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHMULA)),
                ],
                'NOSIRI',
                [
                    'attribute' => 'JENISPREMIS',
                    'value' => OptionHandler::resolve('jenis-premis', $model->JENISPREMIS),
                ],  
                [
                    'attribute' => 'PRGNLOKASI_AM',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI_AM))? $model->PRGNLOKASI_AM: null;
                    }
                ],
                [
                    'attribute' => 'IDDUN',
                    'value' => function ($model){
                        return (!empty($model->dun0->PRGNDUN))? $model->dun0->PRGNDUN: null;
                    }
                ],
                [
                    'attribute' => 'V_LOKALITI',
                    'value' => function ($model){
                        return (!empty($model->lokaliti->PRGN))? $model->lokaliti->PRGN : null;
                    }
                ],
                [
                    'attribute' => 'LATITUD',
                    'value' => function ($model){
                        return (!empty($model->LATITUD))? $model->LATITUD: null;
                    }
                ],
                [
                    'attribute' => 'PGNDAFTAR',
                    'value' => $model->createdByUser->NAMA,
                ],
                [
                    'attribute' => 'PGNAKHIR',
                    'value' => $model->createdByUser->NAMA,
                ],
                
            ],            
        ])?>
    </div>
    
<!-- Maklumat Lawatan Premis on right side -->
    <!-- <div style="float:left; margin-left:12px; width:50%;"> -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Tamat Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHTAMAT)),
                ],
                [
                    'attribute' => 'IDMODULE',
                    'value' => function ($model){
                        return (!empty($model->idmodule->PRGN))? $model->idmodule->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'ID_TUJUAN',
                    'value' => function ($model){
                        return (!empty($model->tujuan0->PRGN))? $model->tujuan0->PRGN: null;
                    }
                ],
                [
                    'label' => 'Alamat',
                    'attribute' => 'PRGNLOKASI',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI))? $model->PRGNLOKASI: null;
                    }
                ],
                [
                    'attribute' => 'NOADUAN',
                    'value' => function ($model){
                        return (!empty($model->NOADUAN))? $model->NOADUAN: null;
                    }
                ],
                [
                    'attribute' => 'LONGITUD',
                    'value' => function ($model){
                        return (!empty($model->LONGITUD))? $model->LONGITUD: null;
                    }
                ],                
                'TRKHDAFTAR:datetime',
                'TRKHAKHIR:datetime',
            ],
        ]) ?>
    </div>
<!-- End Maklumat Lawatan Premis -->
<?php endif ?>
<!-- ============================================= End display Maklumat Lawatan Premis for Pencegahan Vektor ============================================ -->


<!-- ============================== Display data Maklumat Lawatan Premis for Kawalan Perniagaan & Periksa Tandas ======================================== -->
<?php if ($model->IDMODULE == 'KPN' || $model->IDMODULE == 'PTS'): ?>
<br><br>
<!-- Maklumat Lawatan Premis on left side -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Lawatan Premis</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Mula Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHMULA)),
                ],
                'NOSIRI',
                [
                    'attribute' => 'JENISPREMIS',
                    'value' => OptionHandler::resolve('jenis-premis', $model->JENISPREMIS),
                ],  
                [
                    'attribute' => 'PRGNLOKASI_AM',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI_AM))? $model->PRGNLOKASI_AM: null;
                    }
                ],
                [
                    'attribute' => 'IDDUN',
                    'value' => function ($model){
                        return (!empty($model->dun0->PRGNDUN))? $model->dun0->PRGNDUN: null;
                    }
                ],
                [
                    'attribute' => 'LATITUD',
                    'value' => function ($model){
                        return (!empty($model->LATITUD))? $model->LATITUD: null;
                    }
                ],
                [
                    'attribute' => 'IDLOKASI',
                    'value' => function ($model){
                        return (!empty($model->lokasi0->NAME))? $model->lokasi0->NAME: null;
                    }
                ],
                [
                    'attribute' => 'PGNDAFTAR',
                    'value' => $model->createdByUser->NAMA,
                ],
                [
                    'attribute' => 'PGNAKHIR',
                    'value' => $model->createdByUser->NAMA,
                ],
                
            ],            
        ])?>
    </div>
    
<!-- Maklumat Lawatan Premis on right side -->
    <!-- <div style="float:left; margin-left:12px; width:50%;"> -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Tamat Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHTAMAT)),
                ],
                [
                    'attribute' => 'IDMODULE',
                    'value' => function ($model){
                        return (!empty($model->idmodule->PRGN))? $model->idmodule->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'ID_TUJUAN',
                    'value' => function ($model){
                        return (!empty($model->tujuan0->PRGN))? $model->tujuan0->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'NOADUAN',
                    'value' => function ($model){
                        return (!empty($model->NOADUAN))? $model->NOADUAN: null;
                    }
                ],
                [
                    'attribute' => 'LONGITUD',
                    'value' => function ($model){
                        return (!empty($model->LONGITUD))? $model->LONGITUD: null;
                    }
                ],
                'CATATAN',                
                'TRKHDAFTAR:datetime',
                'TRKHAKHIR:datetime',
            ],
        ]) ?>
    </div>
<!-- End Maklumat Lawatan Premis -->
<?php endif ?>
<!-- ========================== End display Maklumat Lawatan Premis for Kawalan Perniagaan & Periksa Tandas ================================ -->


<!-- ================================ Display data Maklumat Lawatan Premis for Pemeriksaan Premis ============================================= -->
<?php if ($model->IDMODULE == 'PPM'): ?>
<br><br>
<!-- Maklumat Lawatan Premis on left side -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd; font-color:#333;"><th style="text-align:center;"><h5>Maklumat Lawatan Premis</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Mula Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHMULA)),
                ],
                'NOSIRI',
                [
                    'attribute' => 'JENISPREMIS',
                    'value' => OptionHandler::resolve('jenis-premis', $model->JENISPREMIS),
                ],  
                [
                    'attribute' => 'PRGNLOKASI_AM',
                    'value' => function ($model){
                        return (!empty($model->PRGNLOKASI_AM))? $model->PRGNLOKASI_AM: null;
                    }
                ],
                [
                    'attribute' => 'IDDUN',
                    'value' => function ($model){
                        return (!empty($model->dun0->PRGNDUN))? $model->dun0->PRGNDUN: null;
                    }
                ],
                [
                    'attribute' => 'PPM_SUNTIKAN_ANTITIFOID',
                    'value' => function ($model){
                        return (!empty($model->PPM_SUNTIKAN_ANTITIFOID))? $model->PPM_SUNTIKAN_ANTITIFOID: null;
                    }
                ],
                [
                    'attribute' => 'LATITUD',
                    'value' => function ($model){
                        return (!empty($model->LATITUD))? $model->LATITUD: null;
                    }
                ],
                [
                    'attribute' => 'IDLOKASI',
                    'value' => function ($model){
                        return (!empty($model->lokasi0->LOCATION_NAME))? $model->lokasi0->LOCATION_NAME: null;
                    }
                ],
                [
                    'attribute' => 'PGNDAFTAR',
                    'value' => $model->createdByUser->NAMA,
                ],
                [
                    'attribute' => 'PGNAKHIR',
                    'value' => $model->createdByUser->NAMA,
                ],
                
            ],            
        ])?>
    </div>
    
<!-- Maklumat Lawatan Premis on right side -->
    <!-- <div style="float:left; margin-left:12px; width:50%;"> -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'label' => 'Tarikh Tamat Aktiviti',
                    'value' => date('d-m-Y H:i', strtotime($model->TRKHTAMAT)),
                ],
                [
                    'attribute' => 'IDMODULE',
                    'value' => function ($model){
                        return (!empty($model->idmodule->PRGN))? $model->idmodule->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'ID_TUJUAN',
                    'value' => function ($model){
                        return (!empty($model->tujuan0->PRGN))? $model->tujuan0->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'NOADUAN',
                    'value' => function ($model){
                        return (!empty($model->NOADUAN))? $model->NOADUAN: null;
                    }
                ],
                [
                    'attribute' => 'PPM_BILPENGENDALI',
                    'value' => function ($model){
                        return (!empty($model->PPM_BILPENGENDALI))? $model->PPM_BILPENGENDALI: null;
                    }
                ],
                [
                    'attribute' => 'PPM_KURSUS_PENGENDALI',
                    'value' => function ($model){
                        return (!empty($model->PPM_KURSUS_PENGENDALI))? $model->PPM_KURSUS_PENGENDALI: null;
                    }
                ],
                [
                    'attribute' => 'LONGITUD',
                    'value' => function ($model){
                        return (!empty($model->LONGITUD))? $model->LONGITUD: null;
                    }
                ],  
                'CATATAN',        
                'TRKHDAFTAR:datetime',
                'TRKHAKHIR:datetime',
            ],
        ]) ?>
    </div>
<!-- End Maklumat Lawatan Premis -->
<?php endif ?>
<!-- =================================== End display Maklumat Lawatan Premis for Pemeriksaan Premis ========================================== -->


<!-- ================================== Maklumat Tambahan Pemeriksaan Untuk Pemeriksaan Kolam ===============================================-->
<?php if ($model->IDMODULE == 'PKK'): ?>
<table style="height:0%; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Maklumat Tambahan Pemeriksaan</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 60%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'PKK_NAMAPENYELIA',
                    'value' => function ($model){
                        return (!empty($model->PKK_NAMAPENYELIA))? $model->PKK_NAMAPENYELIA: null;
                    }
                ],
                [
                    'attribute' => 'PKK_NOKPPENYELIA',
                    'value' => function ($model){
                        return (!empty($model->PKK_NOKPPENYELIA))? $model->PKK_NOKPPENYELIA: null;
                    }
                ],
                [
                    'attribute' => 'PKK_JUMPENGGUNA',
                    'value' => function ($model){
                        return (!empty($model->PKK_JUMPENGGUNA))? $model->PKK_JUMPENGGUNA: null;
                    }
                ],
                
            ],
            ]) ?>
    </div>

    <!-- Maklumat Lawatan Premis on right side -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'PKK_NOKPPENYELIA',
                    'value' => function ($model){
                        return (!empty($model->PKK_NOKPPENYELIA))? $model->PKK_NOKPPENYELIA: null;
                    }
                ],
                [
                    'attribute' => 'PKK_JENISRAWATAN',
                    'value' => OptionHandler::resolve('jenisrawatan', $model->PKK_JENISRAWATAN),
                ],
                [
                    'attribute' => 'CATATAN',
                    'value' => function ($model){
                        return (!empty($model->CATATAN))? $model->CATATAN: null;
                    }
                ],
            ],
        ]) ?>
    </div>
<?php endif ?>
<!-- ================================== End Maklumat Tambahan Pemeriksaan Untuk Pemeriksaan Kolam ==============================================-->


<!-- ========================================== Maklumat Kes/Aktiviti Untuk Semburan SRT ==================================================-->
<?php if ($model->IDMODULE == 'SRT'): ?>
<table style="height:0%; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Maklumat Kes/Aktiviti</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'V_RUJUKANKES',
                    'value' => function ($model){
                        return (!empty($model->V_RUJUKANKES))? $model->V_RUJUKANKES: null;
                    }
                ],
                [
                    'attribute' => 'V_NOWABAK',
                    'value' => function ($model){
                        return (!empty($model->V_NOWABAK))? $model->V_NOWABAK: null;
                    }
                ],
                [
                    'attribute' => 'V_MINGGUEPID',
                    'value' => function ($model){
                        return (!empty($model->V_MINGGUEPID))? $model->V_MINGGUEPID: null;
                    }
                ],
                
            ],
            ]) ?>
    </div>

    <!-- Maklumat Lawatan Premis on right side -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'SRT_ID_SEMBURANSRT',
                    'value' => function ($model){
                        return (!empty($model->jenisSemburan->PRGN))? $model->jenisSemburan->PRGN: null;
                    }
                ],
                [
                    'attribute' => 'V_NOAKTIVITI',
                    'value' => function ($model){
                        return (!empty($model->V_NOAKTIVITI))? $model->V_NOAKTIVITI: null;
                    }
                ],
                [
                    'attribute' => 'CATATAN',
                    'value' => function ($model){
                        return (!empty($model->CATATAN))? $model->CATATAN: null;
                    }
                ],
            ],
        ]) ?>
    </div>
<?php endif ?>
<!-- ======================================== End Maklumat Kes/Aktiviti Untuk Semburan Termal(SRT) ================================================-->


<!-- ============================================= Maklumat Kes/Aktiviti Untuk Semburan ULV =======================================================-->
<?php if ($model->IDMODULE == 'ULV'): ?>
<table style="height:0%; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Maklumat Kes/Aktiviti</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'V_RUJUKANKES',
                    'value' => function ($model){
                        return (!empty($model->V_RUJUKANKES))? $model->V_RUJUKANKES: null;
                    }
                ],
                [
                    'attribute' => 'V_NODAFTARKES',
                    'value' => function ($model){
                        return (!empty($model->V_NODAFTARKES))? $model->V_NODAFTARKES: null;
                    }
                ],
                [
                    'attribute' => 'V_NOAKTIVITI',
                    'value' => function ($model){
                        return (!empty($model->V_NOAKTIVITI))? $model->V_NOAKTIVITI: null;
                    }
                ],
                [
                    'format' => 'datetime',
                    'attribute' => 'V_TRKHKEYIN',
                ],
            ],
            ]) ?>
    </div>

    <!-- Maklumat Lawatan Premis on right side -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'V_NOWABAK',
                    'value' => function ($model){
                        return (!empty($model->V_NOWABAK))? $model->V_NOWABAK: null;
                    }
                ],
                [
                    'format' => 'datetime',
                    'attribute' => 'ULV_TRKHONSET',
                ],
                [
                    'format' => 'datetime',
                    'attribute' => 'V_TRKHNOTIFIKASI',
                ],
                [
                    'attribute' => 'CATATAN',
                    'value' => function ($model){
                        return (!empty($model->CATATAN))? $model->CATATAN: null;
                    }
                ],
            ],
        ]) ?>
    </div>
<?php endif ?>
<!-- ============================================ End Maklumat Kes/Aktiviti Untuk Semburan ULV ===================================================-->


<!-- ========================================== Maklumat Kes/Aktiviti Untuk Aedes & Larvaciding ==================================================-->
<?php if ($model->IDMODULE == 'PTP' || $model->IDMODULE == 'LVC'): ?>
<table style="height:0%; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Maklumat Kes/Aktiviti</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'V_RUJUKANKES',
                    'value' => function ($model){
                        return (!empty($model->V_RUJUKANKES))? $model->V_RUJUKANKES: null;
                    }
                ],
                [
                    'attribute' => 'V_NOWABAK',
                    'value' => function ($model){
                        return (!empty($model->V_NOWABAK))? $model->V_NOWABAK: null;
                    }
                ],
                [
                    'format' => 'datetime',
                    'attribute' => 'V_TRKHKEYIN',
                ],
                [
                    'attribute' => 'V_MINGGUEPID',
                    'value' => function ($model){
                        return (!empty($model->V_MINGGUEPID))? $model->V_MINGGUEPID: null;
                    }
                ],
                
            ],
            ]) ?>
    </div>

    <!-- Maklumat Lawatan Premis on right side -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'V_NODAFTARKES',
                    'value' => function ($model){
                        return (!empty($model->V_NODAFTARKES))? $model->V_NODAFTARKES: null;
                    }
                ],
                [
                    'attribute' => 'V_NOAKTIVITI',
                    'value' => function ($model){
                        return (!empty($model->V_NOAKTIVITI))? $model->V_NOAKTIVITI: null;
                    }
                ],
                [
                    'format' => 'datetime',
                    'attribute' => 'V_TRKHNOTIFIKASI',
                ],
                [
                    'attribute' => 'CATATAN',
                    'value' => function ($model){
                        return (!empty($model->CATATAN))? $model->CATATAN: null;
                    }
                ],
            ],
        ]) ?>
    </div>
<?php endif ?>
<!-- ======================================== End Maklumat Kes/Aktiviti Untuk Semburan Termal(SRT) ================================================-->

<!-- add to display section for PTP and remove info larvaciding for LVC -NOR10012023 -->
<!-- ======================================= Tujuan Aktiviti for LVC Dan PTP ===============================================-->
<?php if ($model->IDMODULE == 'LVC' || $model->IDMODULE == 'PTP'): ?> 

<!-- Maklumat Tujuan Aktiviti -->
<table style="height:0%; width:100%;">
    <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Maklumat Tujuan Aktiviti</h5></th></tr>
</table>

<div style="float:left; height: 10%; width:50%;">
    <?= DetailView::widget([
        'model' => $model,
        'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
        'attributes' => [
            [
                'attribute' => 'V_JENISSEMBUR',
                'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('jenis-sembur', $model->V_JENISSEMBUR),
            ],
            'V_PUSINGAN',
            [
                'attribute' => 'V_TEMPOH',
                'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('tempoh', $model->V_TEMPOH),
            ],
        ],
        ]) ?>
</div>

<!-- Maklumat Tujuan Aktiviti on right side -->
<div style="float:left; height: 10%; width:50%;">
    <?= DetailView::widget([
        'model' => $model,
        'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
        'attributes' => [
            [
                'attribute' => 'V_KATLOKALITI',
                'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('kat-lokaliti', $model->V_KATLOKALITI),
            ],
            [
                'attribute' => 'V_ID_SUREVEILAN',
                'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('ptp-surveilan', $model->V_ID_SUREVEILAN),
            ],
            [
                'attribute' => 'V_ID_ALASAN',
                'value' => function ($model){
                    return (!empty($model->alasan->PRGN))? $model->alasan->PRGN: null;
                }
            ],
        ],
        ]) ?>
</div>
<?php endif ?>
<!-- ========================================== End Maklumat Larvaciding & Tujuan Aktiviti for LVC ==================================================-->


<!-- ===================== Display data Maklumat Lawatan Premis Yang Diperiksa EXCEPT for Kawalan Perniagaan & Vektor ======================== -->
    <?php if ($model->IDMODULE !='KPN' && $model->IDMODULE != 'SRT' && $model->IDMODULE != 'ULV' && $model->IDMODULE != 'PTP' && $model->IDMODULE != 'LVC'): ?>
    <!-- Maklumat Lawatan Premis yang diperiksa on left side -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Maklumat Lawatan Premis Yang Diperiksa</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'NOLESEN',
                    'label' => 'No. Lesen',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->NOLESEN))? $model->pemilik0->NOLESEN: null;
                    }
                ],
                [  
                    'label' => 'Kategori Lesen',
                    'attribute' => 'KETERANGANKATEGORI',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->KETERANGAN_KATEGORI))? $model->pemilik0->KETERANGAN_KATEGORI: null;
                    }
                ],
                [
                    'label' => 'Nama Syarikat',
                    'attribute' => 'NAMASYARIKAT',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->NAMASYARIKAT))? $model->pemilik0->NAMASYARIKAT: null;
                    }
                ],
                [
                    'label' => 'Nama Pemilik/Pemohon',
                    'attribute' => 'NAMAPEMOHON',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->NAMAPEMOHON))? $model->pemilik0->NAMAPEMOHON: null;
                    }
                ],
                [
                    'label' => 'Alamat Premis',
                    'attribute' => 'ALAMAT1',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->ALAMAT1))? $model->pemilik0->ALAMAT1: null;
                    }
                ],
                [
                    'label' => 'Alamat Premis',
                    'attribute' => 'ALAMAT2',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->ALAMAT2))? $model->pemilik0->ALAMAT2: null;
                    }
                ],
                [
                    'label' => 'Alamat Premis',
                    'attribute' => 'ALAMAT3',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->ALAMAT3))? $model->pemilik0->ALAMAT3: null;
                    }
                ],
            ],
        ]) ?>
    </div>
 
<!-- Maklumat Lawatan Premis on right side -->
    <!-- <div style="float:left; margin-left:12px; width:50%;"> -->
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'NOSSM',
                    'label' => 'No. SSM',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->NOSSM))? $model->pemilik0->NOSSM: null;
                    }
                ],
                [  
                    'attribute' => 'JENIS_PREMIS',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->JENIS_PREMIS))? $model->pemilik0->JENIS_PREMIS: null;
                    }
                ],
                [
                    'label' => 'Nama Premis',
                    'attribute' => 'NAMAPREMIS',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->NAMAPREMIS))? $model->pemilik0->NAMAPREMIS: null;
                    }
                ],
                [
                    'label' => 'No. KP Pemilik/Pemohon',
                    'attribute' => 'NOKPPEMOHON',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->NOKPPEMOHON))? $model->pemilik0->NOKPPEMOHON: null;
                    }
                ],
                [
                    'label' => 'Jenis Jualan',
                    'attribute' => 'JENISJUALAN',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->JENIS_JUALAN))? $model->pemilik0->JENIS_JUALAN: null;
                    }
                ],
                [
                    'label' => 'No. Telefon',
                    'attribute' => 'NOTEL',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->NOTEL))? $model->pemilik0->NOTEL: null;
                    }
                ],
                [
                    'attribute' => 'POSKOD',
                    'value' => function ($model){
                        return (!empty($model->pemilik0->POSKOD))? $model->pemilik0->POSKOD: null;
                    }
                ],
            ],
        ]) ?>
    </div>
<!-- End Maklumat Lawatan Premis -->
<?php endif ?>
<!-- ========================= End display data Maklumat Lawatan Premis Yang Diperiksa EXCEPT for Kawalan Perniagaan ========================= -->


<!-- ================================================ Maklumat tambahan untuk semburan ULV =================================================== -->
<?php if ($model->IDMODULE =='ULV'): ?>
<!-- Jenis Semburan -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Jenis Semburan</h5></th></tr>
    </table>
        <div style="float:left; width:50%;">
            <?= DetailView::widget([
                'model' => $model,
                'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
                'attributes' => [
                    [
                        'attribute' => 'V_JENISSEMBUR',
                        'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('jenis-sembur', $model->V_JENISSEMBUR),
                    ],
                    'V_PUSINGAN',                
                ],
            ]) ?>  
        </div> 
        
        <div style="float:right; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'V_KATLOKALITI',
                    'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('kat-lokaliti', $model->V_KATLOKALITI),
                ],
                [
                    'attribute' => 'V_ID_SUREVEILAN',
                    'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('ulv-surveilan', $model->V_ID_SUREVEILAN),
                ],
            ],
            ]) ?>
        </div> 

<!-- Cuaca -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Cuaca</h5></th></tr>
    </table>
        <div style="float:left; width:50%;">
            <?= DetailView::widget([
                'model' => $model,
                'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
                'attributes' => [
                    [
                        'attribute' => 'ULV_HUJAN',
                        'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('hujan', $model->ULV_HUJAN),
                    ],
                    [
                        'attribute' => 'ULV_KEADAANHUJAN',
                        'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('keadaan-hujan', $model->ULV_KEADAANHUJAN),
                    ],
                    [
                        'format' => 'datetime',
                        'attribute' => 'ULV_MASAMULAHUJAN',
                    ],
                    [
                        'format' => 'datetime',
                        'attribute' => 'ULV_MASATAMATHUJAN',
                    ],               
                ],
            ]) ?>  
        </div> 
        
        <div style="float:right; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'ULV_ANGIN',
                    'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('hujan', $model->ULV_ANGIN),
                ],
                [
                    'attribute' => 'ULV_KEADAANANGIN',
                    'value' => \backend\modules\vektor\utilities\OptionHandler::resolve('keadaan-angin', $model->ULV_KEADAANANGIN),
                ],
                [
                    'format' => 'datetime',
                    'attribute' => 'ULV_MASAMULAANGIN',
                ],
                [
                    'format' => 'datetime',
                    'attribute' => 'ULV_MASATAMATANGIN',
                ],
            ],
            ]) ?>
        </div> 

<!-- Maklumat ULV -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Maklumat ULV</h5></th></tr>
    </table>
        <div style="float:left; width:50%;">
            <?= DetailView::widget([
                'model' => $model,
                'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
                'attributes' => [
                    [  
                        'attribute' => 'ULV_ID_RACUN',
                        'value' => function ($model){
                            return (!empty($model->racun->PRGN))? $model->racun->PRGN: null;
                        }
                    ],
                    [  
                        'attribute' => 'ULV_ID_PELARUT',
                        'value' => function ($model){
                            return (!empty($model->pelarut->PRGN))? $model->pelarut->PRGN: null;
                        }
                    ],               
                    [  
                        'attribute' => 'ULV_BILMESIN',
                        'value' => function ($model){
                            return (!empty($model->ULV_BILMESIN))? $model->ULV_BILMESIN: null;
                        }
                    ],
                ],
            ]) ?>  
        </div> 
        
        <div style="float:right; width:50%;">
            <?= DetailView::widget([
                'model' => $model,
                'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
                'attributes' => [
                    [  
                        'attribute' => 'ULV_AMAUNRACUN',
                        'value' => function ($model){
                            return (!empty($model->ULV_AMAUNRACUN))? $model->ULV_AMAUNRACUN: null;
                        }
                    ],
                    [  
                        'attribute' => 'ULV_AMAUNPELARUT',
                        'value' => function ($model){
                            return (!empty($model->ULV_AMAUNPELARUT))? $model->ULV_AMAUNPELARUT: null;
                        }
                    ],
                    [  
                        'attribute' => 'ULV_AMAUNPETROL',
                        'value' => function ($model){
                            return (!empty($model->ULV_AMAUNPETROL))? $model->ULV_AMAUNPETROL: null;
                        }
                    ],
                ],
            ]) ?>
        </div> 
<?php endif ?>


<!-- Maklumat Pasukan/Pegawai Operasi -->
<table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Maklumat Pasukan/Pegawai Operasi</h5></th></tr>
    </table>
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 15%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                [
                    'attribute' => 'KETUAPASUKAN',
                    'label' => 'Ketua Pasukan',
                    'value' => function ($model) {
                        return (!empty($model->ketuapasukan0->pengguna0->NAMA))? $model->ketuapasukan0->pengguna0->NAMA: null;
                        // if ($KETUAPASUKAN = $model->ketuapasukan0->pengguna0->NAMA) {
                        //     return $KETUAPASUKAN;
                    }
                ],
                [
                    'attribute' => 'ahlipasukan',
                    'label' => 'Ahli Pasukan',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->ahlipasukan)
                            return implode('<br />', $model->ahlipasukan);
                    },
                ],
            ],
        ]) ?>  
<!-- End Maklumat Pasukan/Pegawai Operasi -->



<!-- ====================== Display data Maklumat Penerima EXCEPT for Kawalan Perniagaan, Periksa Premis & Vektor =========================== -->
<?php if ($model->IDMODULE !='KPN' && $model->IDMODULE !='PPM' && $model->IDMODULE != 'SRT' && $model->IDMODULE != 'ULV' && $model->IDMODULE != 'PTP' && $model->IDMODULE != 'LVC'): ?>
<!-- Maklumat Penerima -->
<!-- <div style="float:left; margin-left:12px; width:50%;"> -->
    <table style="height:40px; width:100%;">
        <tr style="background-color: #b6e7cf; border: 1px solid #ddd;"><th style="text-align:center;"><h5>Maklumat Penerima</h5></th></tr>
    </table>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                'NAMAPENERIMA',
                // 'NOKPPENERIMA',
            ],
        ]) ?>
    </div>
    <div style="float:left; width:50%;">
        <?= DetailView::widget([
            'model' => $model,
            'template' => "<tr><th style='width: 30%;'>{label}</th><td>{value}</td></tr>",
            'attributes' => [
                // 'NAMAPENERIMA',
                'NOKPPENERIMA',
            ],
        ]) ?>
    </div>
<!-- End Maklumat Penerima -->
<?php endif ?>
<!-- ========================== End display data Maklumat Penerima EXCEPT for Kawalan Perniagaan & Periksa Premis =========================== -->
