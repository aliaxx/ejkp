<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

use kartik\widgets\DatePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use common\utilities\OptionHandler;
use codetitan\widgets\LookupInput;


/**
 * @var View $this
 * @var Kompaun $model
 */

 //lookup for no.lesen
$url['NOLESEN'] = Url::to(['/lookup/vektor/lesen', 'target' => 'lawatanmain-nolesen']); //target -> nama object, akan panggil kat js script.
$initVal['NOLESEN'] = null;
// var_dump($idlokasi);
    // var_dump($form->field($model, 'IDLOKASI'));
    // exit;
 //lookup for no.lesen
 //$url['NOSEWA'] = Url::to(['/lookup/sewa/sewa', 'target' => 'lawatanmain-nosewa']); //target -> nama object, akan panggil kat js script.
 $url['NOSEWA'] = Url::to(['/lookup/sewa/sewa','target' => 'lawatanmain-nosewa']); //target -> nama object, akan panggil kat js script.
 
 //$url['NOSEWA'] = Url::to(['/lookup/sewa/sewa?idlokasi=40', 'target' => 'lawatanmain-nosewa']);
//  $initVal['NOSEWA'] = null;

    //GET RECORD FROM TBLAWATAN_PEMILIK
    if($model->pemilik0){

        // var_dump($model->pemilik0);
        // exit();
        $initVal['PEMILIK'] = $model->pemilik0;
        $model->NOLESEN = $initVal['PEMILIK']->NOLESEN;
        $model->NOLESEN1 = $initVal['PEMILIK']->NOLESEN;
        $model->NOSSM = $initVal['PEMILIK']->NOSSM;
        $model->KETERANGANKATEGORI = $initVal['PEMILIK']->KETERANGAN_KATEGORI;
        $model->JENISJUALAN = $initVal['PEMILIK']->JENIS_JUALAN;
        $model->NAMAPEMOHON = $initVal['PEMILIK']->NAMAPEMOHON;
        $model->NOKPPEMOHON = $initVal['PEMILIK']->NOKPPEMOHON;
        $model->NAMASYARIKAT = $initVal['PEMILIK']->NAMASYARIKAT;
        $model->NAMAPREMIS = $initVal['PEMILIK']->NAMAPREMIS;
        $model->ALAMAT1 = $initVal['PEMILIK']->ALAMAT1;
        $model->ALAMAT2 = $initVal['PEMILIK']->ALAMAT2;
        $model->ALAMAT3 = $initVal['PEMILIK']->ALAMAT3;
        $model->POSKOD = $initVal['PEMILIK']->POSKOD;
        $model->JENIS_PREMIS = $initVal['PEMILIK']->JENIS_PREMIS;
        $model->NOTEL = $initVal['PEMILIK']->NOTEL;
        $model->NOSEWA = $initVal['PEMILIK']->NOSEWA;
        $model->NOSEWA1 = $initVal['PEMILIK']->NOSEWA;
        $model->NOPETAK = $initVal['PEMILIK']->NOPETAK;
        $model->NAMAPEMOHON = $initVal['PEMILIK']->NAMAPEMOHON;
        $model->LOKASIPETAK = $initVal['PEMILIK']->LOKASIPETAK;
        $model->JENISSEWA = $initVal['PEMILIK']->JENISSEWA;

    }  

// var_dump($model);
// exit;
?>

<!-- Maklumat premis yang diperiksa -->
<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
        <?= Yii::t('app', 'Maklumat Premis Yang Diperiksa') ?>
            <span class="pull-right"><i id="toggle-opt" class="fa fa-plus" style="cursor:pointer"></i></span>
        </div>
            <div class="panel-body" id="search-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'STATUSLESENPREMIS')->radioList(OptionHandler::render('status-lesen-premis'), [
                            'item' => function ($index, $label, $name, $checked, $value) {
                            //return '<div>' . Html::radio($name, $checked, ['value' => $value, 'id' => 'status-lesen-premis' . $index]) . ' <label class="control-label">' . $label . '</label></div>';
                            return Html::radio($name, $checked, ['value' => $value, 'id' => 'status-lesen-premis' . $index]) . ' <label class="control-label">' . $label . '</label>';
                        }
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'JENISCARIAN')->radioList(OptionHandler::render('jenis-carian'), [
                            'item' => function ($index, $label, $name, $checked, $value) {
                            //return '<div>' . Html::radio($name, $checked, ['value' => $value, 'id' => 'jenis-carian' . $index]) . ' <label class="control-label">' . $label . '</label></div>';
                            return Html::radio($name, $checked, ['value' => $value, 'id' => 'jenis-carian' . $index]) . ' <label class="control-label">' . $label . '</label>';
                        }
                        ]) ?>

                        <div id="divLesen" style="display:<?= $model->JENISCARIAN == 1 ? 'block' : 'none' ?>">
                            <?= $form->field($model, 'NOLESEN')->widget(LookupInput::classname(), [
                                'url' => $url['NOLESEN'],
                            ])->label('Carian Lesen') ?>
                        </div>
                    
                        <div id="divSewa" style="display:<?= $model->JENISCARIAN == 2 ? 'block' : 'none' ?>">
                            <?= $form->field($model, 'NOSEWA')->widget(LookupInput::classname(), [
                                'url' => $url['NOSEWA'],

                            ])->label('Carian Sewa') ?>
                        </div> 
                    </div>
                </div>

                  <!--MAKLUMAT LESEN !-->
                <h4 style="margin-top:0px;">&nbsp;&nbsp;&nbsp;<u>Maklumat Lesen</u></h4>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'NOLESEN1') ->textInput(['readonly'=> true, 'maxlength' => true])->label('No Lesen')?>  
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'NOSSM') ->textInput(['readonly'=> false, 'maxlength' => true])->label('No Daftar SSM')?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'KETERANGANKATEGORI') ->textInput(['readonly' => true])->label('Kategori Lesen')?>
                        <?= $form->field($model, 'KATEGORILESEN')->hiddenInput()->label(false)?>
                        <?= $form->field($model, 'KUMPULAN_LESEN')->hiddenInput()->label(false)?>
                        <?= $form->field($model, 'KETERANGAN_KUMPULAN')->hiddenInput()->label(false)?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'JENIS_PREMIS') ->textInput(['readonly' => true])->label('Jenis Premis')?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'NAMASYARIKAT') ->textInput(['maxlength' => true])->label('Nama Syarikat')?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'NAMAPREMIS') ->textInput(['maxlength' => true])->label('Nama Premis')?>
                    </div>
                </div>
                    
                <!--MAKLUMAT SEWA !-->
                <h4 style="margin-top:0px;">&nbsp;&nbsp;&nbsp;<u>Maklumat Sewa</u></h4>        
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'NOSEWA1') ->textInput(['readonly'=> true, 'maxlength' => true])->label('No Sewa')?>  
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'NOPETAK') ->textInput(['readonly'=> true, 'maxlength' => true])->label('No Petak')?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'LOKASIPETAK') ->textInput(['readonly'=> true, 'maxlength' => true])->label('Lokasi')?>  
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'JENISSEWA') ->textInput(['readonly'=> true, 'maxlength' => true])->label('Jenis Sewa')?>
                    </div>
                </div>

                <!--MAKLUMAT PREMIS !-->
                <h4 style="margin-top:0px;">&nbsp;&nbsp;&nbsp;<u>Maklumat Premis</u></h4> 
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'NAMAPEMOHON') ->textInput(['maxlength' => true])->label('Nama Pemilik/Pemohon')?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'NOKPPEMOHON') ->textInput(['maxlength' => true])->label('No KP Pemilik/Pemohon')?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'ALAMAT1') ->textInput(['maxlength' => true])->label('Alamat Premis 1')?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'JENISJUALAN') ->textInput(['readonly' => true])?> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'ALAMAT2') ->textInput(['maxlength' => true])->label('Alamat Premis 2')?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'NOTEL') ->textInput(['maxlength' => true])->label('No Tel')?> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'ALAMAT3') ->textInput(['maxlength' => true])->label('Alamat Premis 3')?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'POSKOD') ->textInput(['maxlength' => true])?>
                    </div>
                </div>
                    
                    <!-- <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right">
                                <?= Html::a('<i class="fas fa-redo-alt"></i> Set Semula', ['/premis/penggredan-premis/create'], [
                                    'class' => 'btn btn-default',
                                ]) ?>
                                <?= Html::submitButton('<i class="fa fa-search"></i> Cari', [
                                    'class' => 'btn btn-primary',
                                ]) ?>
                            </span>
                        </div>
                    </div> -->
                </div>
            </div>
    </div>
</div>
<?php 


$this->registerJs("
let searchBody = 1;
function toggleSearchBody(toggle)
{
    if (toggle) {
        searchBody = 0;
        $('#search-body').show(600, function (){
            $('#toggle-opt').toggleClass('fa-plus');
            $('#toggle-opt').toggleClass('fa-minus');
        });
    } else {
        searchBody = 1;
        $('#search-body').hide(600, function (){
            $('#toggle-opt').toggleClass('fa-minus');
            $('#toggle-opt').toggleClass('fa-plus');
        });
    }
}

$(document).ready(function () {
    $('#toggle-opt').click(function () {
        toggleSearchBody(searchBody);
    });
});

$(document).ready(function () {
    $('input[id^=\"jenis-carian\"]').click(function (e) {

        //initialize fields
        $('#lawatanmain-nolesen-selection').val(null); 
        $('#lawatanmain-nolesen1').val(null); 
        $('#lawatanmain-namapemohon').val(null); 
        $('#lawatanmain-nokppemohon').val(null);
        $('#lawatanmain-namasyarikat').val(null);
        $('#lawatanmain-nossm').val(null);
        $('#lawatanmain-alamat1').val(null);
        $('#lawatanmain-alamat2').val(null);
        $('#lawatanmain-alamat3').val(null);
        $('#lawatanmain-poskod').val(null);
        $('#lawatanmain-kategorilesen').val(null);
        $('#lawatanmain-keterangankategori').val(null);
        $('#lawatanmain-jenisjualan').val(null);
        $('#lawatanmain-jenis_premis').val(null);
        $('#lawatanmain-nosewa-selection').val(null);
        $('#lawatanmain-nosewa1').val(null);
        $('#lawatanmain-nopetak').val(null);
        $('#lawatanmain-namapemohon').val(null);
        $('#lawatanmain-lokasipetak').val(null);
        $('#lawatanmain-jenissewa').val(null);

        if (this.value == 1) {
            $('#divLesen').show();
        } else {
            $('#divLesen').hide();
        }
        if (this.value == 2) {
            $('#divSewa').show();
        } else {
            $('#divSewa').hide();
        }  
    });
});
");

$this->registerJs("
    
    function toggleLesenLookup() {
        var src = '" . $url['NOLESEN'] . "';
        $('#lookup_frame-lawatanmain-nolesen').attr('src');  //called target that already declare above. 
    } 

    function populateLesen(NOLESEN)
    {
        //{nolesen:NOLESEN} nolesen tu object(akan declare kat controller) : NOLESEN tu value
        $.get('" . Url::to(['/lookup/vektor/lesen-text']) . "', {nolesen: NOLESEN}, function (data) { 
            $('#lawatanmain-nolesen').val(data.results.NO_AKAUN);
            $('#lawatanmain-nolesen1').val(data.results.NO_AKAUN);
            $('#lawatanmain-namapemohon').val(data.results.NAMA_PEMOHON); //$('#object declare kat target').val(model yang nak panggil kat model integrasi lesen)
            $('#lawatanmain-nokppemohon').val(data.results.NO_KP_PEMOHON);
            $('#lawatanmain-namasyarikat').val(data.results.NAMA_SYARIKAT);
            $('#lawatanmain-nossm').val(data.results.NO_DFT_SYKT);
            $('#lawatanmain-alamat1').val(data.results.ALAMAT_PREMIS1);
            $('#lawatanmain-alamat2').val(data.results.ALAMAT_PREMIS2);
            $('#lawatanmain-alamat3').val(data.results.ALAMAT_PREMIS3);
            $('#lawatanmain-poskod').val(data.results.POSKOD);
            $('#lawatanmain-kategorilesen').val(data.results.KATEGORI_LESEN);
            $('#lawatanmain-keterangankategori').val(data.results.KETERANGAN_KATEGORI);
            $('#lawatanmain-jenisjualan').val(data.results.JENIS_JUALAN);
            $('#lawatanmain-jenis_premis').val(data.results.JENIS_PREMIS);
            $('#lawatanmain-kumpulan_lesen').val(data.results.KUMPULAN_LESEN);
            $('#lawatanmain-keterangan_kumpulan').val(data.results.KETERANGAN_KUMPULAN);   
        }); 
    }

    $(document).ready(function() {
        toggleLesenLookup();

        // lookup when triggered
        $('#lawatanmain-nolesen').change(function (e) {
            populateLesen(this.value);
           
    });
})", \yii\web\View::POS_END);

$this->registerJs("
    function toggleSewaLookup() {
        var src = '" . $url['NOSEWA'] . "';
        $('#lookup_frame-lawatanmain-nosewa').attr('src');  //called target that already declare above. 
    } 

    function populateSewaori(nosewa)
    {
       
        //{nosewa:NOSEWA} nosewa tu object(akan declare kat controller) : NOSEWA tu value
        $.get('" . Url::to(['/lookup/sewa/sewa-text']) . "', {nosewa: nosewa}, function (data) { 
            // alert(data.results.LICENSE_NUMBER);
            
            var idlokasi = document.getElementById('lawatanmain-idlokasi').value;
            var idlokasisewa = data.results.LOCATION_ID;
            alert(idlokasi);
            alert(idlokasisewa);
            if (idlokasi != idlokasisewa) {
                alert('Rekod yang dipilih bukan dalam Lokasi yang sama');
            }else{
                // $('#lawatanmain-lokasi').val(data.results.LOCATION_ID);
                $('#lawatanmain-nosewa1').val(data.results.ACCOUNT_NUMBER);
                $('#lawatanmain-nopetak').val(data.results.LOT_NO);
                $('#lawatanmain-nolesen1').val(data.results.LICENSE_NUMBER);
                $('#lawatanmain-namapemohon').val(data.results.NAME);
                
                $('#lawatanmain-lokasipetak').val(data.results.LOCATION_NAME);
                $('#lawatanmain-jenissewa').val(data.results.RENT_CATEGORY);
                $('#lawatanmain-jenisjualan').val(data.results.SALES_TYPE);
                $('#lawatanmain-alamat1').val(data.results.ASSET_ADDRESS_1);
                $('#lawatanmain-alamat2').val(data.results.ASSET_ADDRESS_2);
                $('#lawatanmain-alamat3').val(data.results.ASSET_ADDRESS_3);
                $('#lawatanmain-poskod').val(data.results.ASSET_ADDRESS_POSTCODE);
            };
            
        }); 
    }

    function populateSewa(nosewa)
    {
        //initialize fields
        $('#lawatanmain-nolesen-selection').val(null); 
        $('#lawatanmain-nolesen1').val(null); 
        $('#lawatanmain-namapemohon').val(null); 
        $('#lawatanmain-nokppemohon').val(null);
        $('#lawatanmain-namasyarikat').val(null);
        $('#lawatanmain-nossm').val(null);
        $('#lawatanmain-alamat1').val(null);
        $('#lawatanmain-alamat2').val(null);
        $('#lawatanmain-alamat3').val(null);
        $('#lawatanmain-poskod').val(null);
        $('#lawatanmain-kategorilesen').val(null);
        $('#lawatanmain-keterangankategori').val(null);
        $('#lawatanmain-jenisjualan').val(null);
        $('#lawatanmain-jenis_premis').val(null);
        $('#lawatanmain-nosewa-selection').val(null);
        $('#lawatanmain-nosewa1').val(null);
        $('#lawatanmain-nopetak').val(null);
        $('#lawatanmain-namapemohon').val(null);
        $('#lawatanmain-lokasi').val(null);
        $('#lawatanmain-jenissewa').val(null);


        //{nosewa:NOSEWA} nosewa tu object(akan declare kat controller) : NOSEWA tu value
        $.get('" . Url::to(['/lookup/sewa/sewa-text']) . "', {nosewa: nosewa}, function (data) { 

            const input = document.getElementById('lawatanmain-idlokasi1');
            const idlokasi = input ? input.value : '';
            var idlokasisewa = data.results.LOCATION_ID;

            if (idlokasi && idlokasi != idlokasisewa) { //compare 2 values if idlokasi is visible / selected
                alert('Rekod yang dipilih bukan dalam Lokasi yang sama');
            }else{
                // $('#lawatanmain-lokasipetak').val(data.results.LOCATION_NAME);
                $('#lawatanmain-nosewa').val(data.results.ACCOUNT_NUMBER);
                $('#lawatanmain-nosewa1').val(data.results.ACCOUNT_NUMBER);
                $('#lawatanmain-nopetak').val(data.results.LOT_NO);
                $('#lawatanmain-nolesen1').val(data.results.LICENSE_NUMBER);
                $('#lawatanmain-namapemohon').val(data.results.NAME);
                
                $('#lawatanmain-lokasipetak').val(data.results.LOCATION_NAME);
                $('#lawatanmain-jenissewa').val(data.results.RENT_CATEGORY);
                $('#lawatanmain-jenisjualan').val(data.results.SALES_TYPE);
                $('#lawatanmain-alamat1').val(data.results.ASSET_ADDRESS_1);
                $('#lawatanmain-alamat2').val(data.results.ASSET_ADDRESS_2);
                $('#lawatanmain-alamat3').val(data.results.ASSET_ADDRESS_3);
                $('#lawatanmain-poskod').val(data.results.ASSET_ADDRESS_POSTCODE);
            };
            
        }); 
    }

    $(document).ready(function() {
        toggleSewaLookup();
        
        $('#lawatanmain-nosewa').change(function (e) {
            populateSewa(this.value);            
        });


        // $('#lawatanmain-idlokasi').change(function (e) {        //zihan
        //     $('#lawatanmain-idlokasi1').val(this.value);
        // });

        if (typeof $('#lawatanmain-idlokasi') !== 'undefined') {
            $('#lawatanmain-idlokasi').change(function (e) {        //zihan
                $('#lawatanmain-idlokasi1').val(this.value);
            });
        }

})", \yii\web\View::POS_END);

