<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use common\utilities\DateTimeHelper;

use common\models\Pengguna;
use backend\modules\penyelenggaraan\models\ParamDetail;


//display data Tujuan.
$data['tujuan'] = ['',''];
$source = Yii::$app->db->createCommand("SELECT * FROM TBPARAMETER_DETAIL WHERE KODJENIS = 22")->queryAll();
$data['tujuan'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

//display data Dun.
$data['dun'] = ['',''];
$source = Yii::$app->db->createCommand("SELECT * FROM TBDUN")->queryAll();
$data['dun'] = ArrayHelper::map($source, 'ID', 'PRGNDUN');

//Get data Lokasi Ahli Majlis
$data['ahlimajlis'] = ['',''];
$url['ahlimajlis'] = Url::to(['/option/penyelenggaraan/lokasi-am']);

//Get data jenis persampelan SMM
$source = Yii::$app->db->createCommand("SELECT * FROM TBPARAMETER_DETAIL WHERE KODJENIS=6")->queryAll();
$data['persampelan'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

$url['makmal'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '26']);
$initVal['makmal'] = null;
if ($model->SMM_MAKMAL) {
    $object = ParamDetail::findOne(['KODDETAIL' => $model->SMM_MAKMAL, 'KODJENIS' => '26']);
    $initVal['makmal'] = $object->PRGN;
}

//Get data Tempat Simpanan for SDR
$source = Yii::$app->db->createCommand("SELECT * FROM TBPARAMETER_DETAIL WHERE KODJENIS=24")->queryAll();
$data['tempatsimpanan'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

//Get data lokasi penjaja KPN
$data['penjaja'] = ['',''];
$source = Yii::$app->db->createCommand("SELECT * FROM C##ESEWA.V_LOCATION_LIST")->queryAll();
$data['penjaja'] = ArrayHelper::map($source, 'LOCATION_ID', 'LOCATION_NAME');

//Get data Lokaliti untuk Vektor
$source = Yii::$app->db->createCommand("SELECT * FROM  TBLOKALITI")->queryAll();
$data['lokaliti'] = ArrayHelper::map($source, 'ID', 'PRGN');

//display Aktiviti based on idmodule
$model->IDMODULE=$idModule; 
$sources= Yii::$app->db->createCommand("SELECT PRGN FROM TBMODULE WHERE ID ='$model->IDMODULE' ")->queryOne();
$model->prgnidmodule = $sources['PRGN'];

//allow permission action button to send to controller.
$permission = $idModule . "-write"; 

//Get data jenis semburan for SRT. 
$source = Yii::$app->db->createCommand("SELECT * FROM TBPARAMETER_DETAIL WHERE KODJENIS = 7")->queryAll();
$data['jenissemburan'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

//Get data Jenis Racun for ULV
$url['racun'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '10']);
$initVal['racun'] = null;
if ($model->ULV_ID_RACUN) {
    $object = ParamDetail::findOne(['KODDETAIL' => $model->ULV_ID_RACUN, 'KODJENIS' => '10']);
    $initVal['racun'] = $object->PRGN;
}

//Get data Jenis Pelarut for ULV
$url['pelarut'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '16']);
$initVal['pelarut'] = null;
if ($model->ULV_ID_PELARUT) {
    $object = ParamDetail::findOne(['KODDETAIL' => $model->ULV_ID_PELARUT, 'KODJENIS' => '16']);
    $initVal['pelarut'] = $object->PRGN;
}

//Get data Alasan for LVC
$url['alasan'] = Url::to(['/option/penyelenggaraan/detail', 'KODJENIS' => '13']);
$initVal['alasan'] = null;
if ($model->V_ID_ALASAN) {
    $object = ParamDetail::findOne(['KODDETAIL' => $model->V_ID_ALASAN, 'KODJENIS' => '13']);
    $initVal['alasan'] = $object->PRGN;
}

//GET KATEGORI PREMIS
$data['KATPREMIS'] = ['',''];
$source = Yii::$app->db->createCommand("SELECT * FROM TBPARAMETER_DETAIL WHERE KODJENIS = 1")->queryAll();
$data['KATPREMIS'] = ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

//get current date
date_default_timezone_set("Asia/Kuala_Lumpur");

// Then call the date functions
$date = date('d-m-Y, H:i');

if($model->NOSIRI){
    $model->TRKHMULA = DateTimeHelper::convert($model->TRKHMULA, false, true);
    $model->TRKHTAMAT = DateTimeHelper::convert($model->TRKHTAMAT, false, true);
}else{
    $model->TRKHMULA=$date;
    $model->TRKHTAMAT=$date;
}

$data['KETUAPASUKAN'] = [];
$url['KETUAPASUKAN'] = Url::to(['/option/penyelenggaraan/pengguna']);

$disable['penjaja'] = false;
$model->ULV_TRKHONSET=$date;
$model->V_TRKHKEYIN=$date;
$model->V_TRKHNOTIFIKASI=$date;

if (!$model->isNewRecord) {

    

    $model->ULV_TRKHONSET = DateTimeHelper::convert($model->ULV_TRKHONSET, false, true);
    $model->V_TRKHNOTIFIKASI = DateTimeHelper::convert($model->V_TRKHNOTIFIKASI, false, true);
    $model->V_TRKHKEYIN = DateTimeHelper::convert($model->V_TRKHKEYIN, false, true); 

    $disable['penjaja'] = true;

    // set empty array for ahli pasukan attribute
    $model->ahlipasukan = [];
    // set ketua pasukan
    if ($model->ketuapasukan0) {
        $model->KETUAPASUKAN = $model->ketuapasukan0->IDPENGGUNA;
    }

    // loop pasukanAhlis array and add it inside ahlipasukan attribute
    foreach ($model->pasukanAhlis as $ahli) {
        if ($ahli->JENISPENGGUNA == 2) {
            if (!empty(Pengguna::findOne(['ID' => $ahli->IDPENGGUNA, 'STATUS' => 1]))) {
                $model->ahlipasukan[] = $ahli->IDPENGGUNA;
            }
        }
    }

    // set ketuapasukan data display on dropdown field
    if ($model->ketuapasukan0) {
        $data['KETUAPASUKAN'] = [$model->KETUAPASUKAN => $model->ketuapasukan0->pengguna0->NAMA];
    }

    if ($model->IDZON_AM) {
        //$data['PRGNLOKASI_AM'] = [$model->PRGNLOKASI_AM => $model->PRGNLOKASI_AM];
        $data['ahlimajlis'] = [$model->IDZON_AM . '-' . $model->PRGNLOKASI_AM];
    } 
}

if ($model->isNewRecord) {
   $model->setNoSiri($model->IDMODULE);
}

?>


<?php $form = ActiveForm::begin(['id' => 'lawatanmain-form']); ?>

<?= \codetitan\widgets\ActionBar::widget([
    'permissions' => ['save2close' => Yii::$app->access->can($permission)],
]) ?>


<!-- Maklumat Lawatan Premis -->
<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading"> 
            <?= Yii::t('app', 'Maklumat Lawatan Premis') ?> 
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'TRKHMULA')->textInput()->widget(DateTimePicker::className(), [
                                    'name' => 'TRKHMULA',
                                    'readonly' => true,
                                    'options' => ['class' => 'custom-datetime-field'],
                                    'pluginOptions' => [
                                        'minuzonamep' => 1,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy, hh:ii',
                                        'autoclose' => true,
                                        'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                    ]
                                ]);?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'TRKHTAMAT')->textInput()->widget(DateTimePicker::className(), [
                                'name' => 'TRKHTAMAT',
                                'readonly' => true,
                                'options' => ['class' => 'custom-datetime-field'],
                                'pluginOptions' => [
                                    'minuzonamep' => 1,
                                    'todayHighlight' => true,
                                    'format' => 'dd-mm-yyyy, hh:ii',
                                    //'format' => 'Y-m-d H:i:s',
                                    'autoclose' => true,
                                    'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                ]
                            ]); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOSIRI')->textInput(['disabled' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'prgnidmodule')->textInput(['disabled' => true])->label('Aktiviti',['class'=>'label-class']) ?>
                            <?= $form->field($model, 'IDMODULE')->textInput(['type' => 'hidden'])->label(false) ?>
                        </div>
                    </div>

                    <!-- not display Kategori & tujuan, lokasiahlimajlis on vektor because susunan lain -->
                    <div class="row">
                    <?php if ($model->IDMODULE!="SRT" && $model->IDMODULE!="ULV" && $model->IDMODULE!="PTP" && $model->IDMODULE!="LVC"): ?>
                        <div class="col-md-6">
                            <?= $form->field($model, 'PPM_IDKATPREMIS')->widget(Select2::className(), [
                                    'data' => $data['KATPREMIS'],
                                    'options' => [
                                        'placeholder' => '',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ]
                                ])->label('Kategori Premis') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'ID_TUJUAN')->widget(Select2::className(), [
                                'data' => $data['tujuan'],
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
                            <?= $form->field($model, 'PRGNLOKASI_AM')->widget(Select2::classname(), [
                                //'data' => $data['ahlimajlis'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'ajax' => [
                                        'url' => $url['ahlimajlis'],
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                        'processResults' => new JsExpression('function(data, params) {
                                            params.page = params.page || 1;
                                            return {
                                                results: data.results,
                                                pagination: { more: (params.page * 20) < data.total }
                                            };
                                        }'),
                                    ],
                                ],
                                'options' => [
                                    'placeholder' => '',
                                ],
                            ]); 
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- not display lokasi on kawalan, kolam & vektor -->
                    <?php if ($model->IDMODULE!="KPN" && $model->IDMODULE!="SRT" && $model->IDMODULE!="PTP" && $model->IDMODULE!="ULV" && $model->IDMODULE!="LVC"): ?>
                        <div class="col-md-6">  
                            <?= $form->field($model, 'PRGNLOKASI')->textArea()?>
                        </div>
                    <?php endif; ?>

                    <!-- lokasiahlimajlis, tujuan, alamat on vektor mengikut susunan -->
                    <?php if ($model->IDMODULE=="SRT" || $model->IDMODULE=="PTP" || $model->IDMODULE=="ULV" || $model->IDMODULE=="LVC"): ?>
                            <div class="col-md-6">
                                <?= $form->field($model, 'PRGNLOKASI_AM')->widget(Select2::classname(), [
                                    //'data' => $data['ahlimajlis'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'ajax' => [
                                            'url' => $url['ahlimajlis'],
                                            'dataType' => 'json',
                                            'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                            'processResults' => new JsExpression('function(data, params) {
                                                params.page = params.page || 1;
                                                return {
                                                    results: data.results,
                                                    pagination: { more: (params.page * 20) < data.total }
                                                };
                                            }'),
                                        ],
                                    ],
                                    'options' => [
                                        'placeholder' => '',
                                    ],
                                ]); 
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'ID_TUJUAN')->widget(Select2::className(), [
                                    'data' => $data['tujuan'],
                                    'options' => [
                                        'placeholder' => '',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ]
                                ]) ?>
                            </div>
                            <div class="col-md-12">  
                                <?= $form->field($model, 'PRGNLOKASI')->textArea(['rows' => '3'])->label('Alamat')?>
                            </div>
                    <?php endif; ?>


                    <!-- Display lokasi penjaja on Kawalan & Kolam -->        
                    <?php if ($model->IDMODULE=="KPN"): ?>
                        <div class="col-md-6">
                            <?= $form->field($model, 'IDLOKASI')->widget(Select2::className(), [
                                'data' => $data['penjaja'],
                                'options' => [
                                    'placeholder' => '',
                                    'disabled' => $disable['penjaja'],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ]) ?>
                            <?= $form->field($model, 'IDLOKASI1')->textInput(['type' => 'hidden'])->label(false) ?>
                        </div>
                    <?php endif; ?>  

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'IDDUN')->widget(Select2::className(), [
                                'data' => $data['dun'],
                                'options' => [
                                    'placeholder' => '',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ]) ?>
                        </div>

                        <!-- Display Lokaliti on Vektor -->
                        <?php if ($model->IDMODULE=="SRT" || $model->IDMODULE=="PTP" || $model->IDMODULE=="ULV" || $model->IDMODULE=="LVC"): ?>
                            <div class="col-md-6">
                                    <?= $form->field($model, 'V_LOKALITI')->widget(Select2::className(), [
                                        'data' => $data['lokaliti'],
                                        'options' => [
                                            'placeholder' => '',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ]
                                    ]) ?>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-6">
                            <?= $form->field($model, 'NOADUAN') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'LATITUD')->textInput(['id' => 'lat'])?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'LONGITUD')->textInput(['id' => 'long']) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Display Jenis Persampelan on Sampel Makanan -->
                            <?php if ($model->IDMODULE=="SMM"): ?>
                                <?= $form->field($model, 'SMM_ID_JENISSAMPEL')->widget(Select2::className(), [
                                    'data' => $data['persampelan'],
                                    'options' => [
                                        'placeholder' => '',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ]
                                ]) ?>
                            <!-- Display Tempat Simpanan on Sitaan -->
                            <?php elseif ($model->IDMODULE=="SDR"): ?>
                                <?= $form->field($model, 'SDR_ID_STOR')->widget(Select2::className(), [
                                'data' => $data['tempatsimpanan'],
                                'options' => [
                                    'placeholder' => '',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                                ]) ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if ($model->IDMODULE=="SMM"): ?>
                                <?= $form->field($model, 'SMM_MAKMAL')->widget(Select2::className(), [
                                    'initValueText' => $initVal['makmal'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'placeholder' => '',
                                        'ajax' => [
                                            'url' => $url['makmal'],
                                            'dataType' => 'json',
                                            'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                            'processResults' => new JsExpression('function(data, params) {
                                                params.page = params.page || 1;
                                                return {
                                                    results: data.results,
                                                    pagination: { more: (params.page * 20) < data.total }
                                                };
                                            }'),
                                        ],
                                    ],
                                    // 'options' => ['disabled' => !$model->isNewRecord],
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Display Pengendali Makanan only pemeriksaan premis (PPM) -->
                    <?php if ($model->IDMODULE=="PPM"): ?>
                        <div class="row">
                        <h5 style="margin-top:0px;">&nbsp;&nbsp;&nbsp;<u>Pengendali Makanan</u></h5>
                            <div class="col-md-4">
                            <?= $form->field($model, 'PPM_BILPENGENDALI')->textInput(['type' => 'number', 'min' => 1, 'readonly' => false])->label('Bil. Pengendali')?>
                            </div>
                            <div class="col-md-4">
                            <?= $form->field($model, 'PPM_SUNTIKAN_ANTITIFOID')->textInput(['type' => 'number', 'min' => 1, 'readonly' => false])->label('Suntikan Pelalian Anti-Tifoid') ?>
                            </div>
                            <div class="col-md-4">
                            <?= $form->field($model, 'PPM_KURSUS_PENGENDALI')->textInput(['type' => 'number', 'min' => 1, 'readonly' => false])->label('Kursus Pengendali Makanan') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'CATATAN')->textArea(['rows' => '3'])?>
                            </div>
                            <div class="col-md-6">
                                <?php $checkboxTemplate = '<label class="col-sm-12 control-label pull-right"><b>Penggredan Tandas?</b></label>'; ?>
                                    <?= $form->field($model, 'PTM_ISTANDAS')->checkbox(['style' => 'width: 200%; margin-right: 200%; margin-top: 10px;',
                                        'label' => $checkboxTemplate,
                                        'class' => 'checkbox-inline',
                                        // 'item' => function ($index, $label, $name, $checked, $value) {
                                        //     //return '<div>' . Html::radio($name, $checked, ['value' => $value, 'id' => 'jenis-carian' . $index]) . ' <label class="control-label">' . $label . '</label></div>';
                                        //     return Html::radio($name, $checked, ['value' => $value, 'id' => 'is-tandas' . $index]) . ' <label class="control-label">' . $label . '</label>';
                                        // }
                                        ]) //Jika OKK bersetuju membayar amaun kompaun yang ditawarkan
                                ?>
                                 <!-- <div id="divTandas" style="display:<?= $model->PTM_ISTANDAS == "checked" ? 'block' : 'none' ?>"> -->
                                    <?= $this->render('@backend/modules/vektor/views/tandas/_item_jenistandas', [
                                            'form' => $form,
                                            'model' => $model,
                                    ]) ?>
                                <!-- </div> -->
                               
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($model->IDMODULE=="PTS"): ?>
                        <div class="col-md-6">
                            <?= $this->render('@backend/modules/vektor/views/tandas/_item_jenistandas', [
                                    'form' => $form,
                                    'model' => $model,
                            ]) ?>
                        </div>
                    <?php endif; ?>  

                    
                    
                     <!-- Not display catatan on vektor -->
                    <?php if ($model->IDMODULE!="SRT" && $model->IDMODULE!="PTP" && $model->IDMODULE!="ULV" && $model->IDMODULE!="LVC" && $model->IDMODULE!="PPM"): ?>
                        <div class="row">
                            <div class="col-md-12">  
                                <?= $form->field($model, 'CATATAN')->textArea(['rows' => '3'])?>
                            </div>
                        </div> 
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Maklumat tambahan pemeriksaan KOLAM (PKK) -->
<?php if ($model->IDMODULE=="PKK"): ?>
    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Maklumat Tambahan Pemeriksaan Kolam') ?>
            </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">  
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'PKK_NAMAPENYELIA') ->textInput(['maxlength' => true])?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'PKK_NOKPPENYELIA') ->textInput(['maxlength' => true])?>  
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'PKK_JUMPENGGUNA') ->textInput(['type' => 'number', 'min' => 1])?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'PKK_JENISKOLAM') ->textInput(['maxlength' => true])?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'PKK_JENISRAWATAN')->radioList(backend\modules\makanan\utilities\OptionHandler::render('jenisrawatan'), ['selector'=>'radio']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
<?php endif; ?>


<!-- ===========================================  Maklumat Premis Yang Diperiksa ======================================== -->
					
<?php if ($model->IDMODULE!="SRT" && $model->IDMODULE!="ULV" && $model->IDMODULE!="PTP" && $model->IDMODULE!="LVC" && $model->IDMODULE!="KPN"): ?>
<!-- Maklumat premis yang diperiksa -->
<?= $this->render('@backend/modules/integrasi/views/lesen/_search', [
    'form' => $form,
    'model' => $model,
]) ?>
<?php endif; ?>

<!-- ===========================================  End Maklumat Premis Yang Diperiksa ======================================== -->


<!-- ===========================================  Pencegahan Vektor ======================================== -->
<!-- Maklumat Kes/Aktiviti for SRT -->
<?php if ($model->IDMODULE=="SRT"): ?>
    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Maklumat Kes/Aktiviti') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">  
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'V_RUJUKANKES')->textInput(['maxlength' => true])?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'SRT_ID_SEMBURANSRT')->widget(Select2::className(), [
                                    'data' => $data['jenissemburan'],
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
                                <?= $form->field($model, 'V_NOWABAK')?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'V_NOAKTIVITI') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'V_MINGGUEPID')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">  
                                <?= $form->field($model, 'CATATAN')->textArea(['rows' => '3'])?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Maklumat Kes/Aktiviti for ULV, PTP, LVC -->
<?php if ($model->IDMODULE=="ULV" || $model->IDMODULE=="PTP" || $model->IDMODULE=="LVC"): ?>
    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Maklumat Kes/Aktiviti') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">  
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'V_RUJUKANKES')->textInput(['maxlength' => true])?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'V_NODAFTARKES')->textInput(['maxlength' => true])?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'V_NOWABAK')?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'V_NOAKTIVITI') ?>
                            </div>
                        </div>

                        <!-- Display Tarikh only for Semburan ULV -->
                        <?php if ($model->IDMODULE=="ULV"): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'ULV_TRKHONSET')->textInput()->widget(DateTimePicker::className(), [
                                        'name' => 'ULV_TRKHONSET',
                                        'readonly' => true,
                                        'options' => ['class' => 'custom-datetime-field'],
                                        'pluginOptions' => [
                                            'minuzonamep' => 1,
                                            'todayHighlight' => true,
                                            'format' => 'dd-mm-yyyy, hh:ii',
                                            'autoclose' => true,
                                            'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                        ]
                                    ]);?>
                                </div>

                            <div class="col-md-6">
                                <?= $form->field($model, 'V_TRKHKEYIN')->textInput()->widget(DateTimePicker::className(), [
                                    'name' => 'V_TRKHKEYIN',
                                    'readonly' => true,
                                    'options' => ['class' => 'custom-datetime-field'],
                                    'pluginOptions' => [
                                        'minuzonamep' => 1,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy, hh:ii',
                                        //'format' => 'Y-m-d H:i:s',
                                        'autoclose' => true,
                                        'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                    ]
                                ]); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'V_TRKHNOTIFIKASI')->textInput()->widget(DateTimePicker::className(), [
                                    'name' => 'V_TRKHNOTIFIKASI',
                                    'readonly' => true,
                                    'options' => ['class' => 'custom-datetime-field'],
                                    'pluginOptions' => [
                                        'minuzonamep' => 1,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy, hh:ii',
                                        //'format' => 'Y-m-d H:i:s',
                                        'autoclose' => true,
                                        'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                    ]
                                ]); ?>
                            </div>
                            <?php endif; ?>

                        <!-- Display Tarikh only for PTP & LVC -->
                        <?php if ($model->IDMODULE=="PTP" || $model->IDMODULE=="LVC"): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'V_TRKHKEYIN')->textInput()->widget(DateTimePicker::className(), [
                                        'name' => 'V_TRKHKEYIN',
                                        'readonly' => true,
                                        'options' => ['class' => 'custom-datetime-field'],
                                        'pluginOptions' => [
                                            'minuzonamep' => 1,
                                            'todayHighlight' => true,
                                            'format' => 'dd-mm-yyyy, hh:ii',
                                            //'format' => 'Y-m-d H:i:s',
                                            'autoclose' => true,
                                            'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                        ]
                                    ]); ?>
                                </div>
                        
                                <div class="col-md-6">
                                    <?= $form->field($model, 'V_TRKHNOTIFIKASI')->textInput()->widget(DateTimePicker::className(), [
                                        'name' => 'V_TRKHNOTIFIKASI',
                                        'readonly' => true,
                                        'options' => ['class' => 'custom-datetime-field'],
                                        'pluginOptions' => [
                                            'minuzonamep' => 1,
                                            'todayHighlight' => true,
                                            'format' => 'dd-mm-yyyy, hh:ii',
                                            //'format' => 'Y-m-d H:i:s',
                                            'autoclose' => true,
                                            'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                        ]
                                    ]); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'V_MINGGUEPID')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">  
                                <?= $form->field($model, 'CATATAN')->textArea(['rows' => '3'])?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
<?php endif; ?>

<!-- ********** Jenis Semburan, Cuaca & Maklumat ULV for ULV ************ -->
<?php if ($model->IDMODULE=="ULV"): ?>
<!-- jenis semburan-->
<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">

            <?= Yii::t('app', 'Jenis Semburan') ?>
        </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6" >
                                <?= $form->field($model, 'V_JENISSEMBUR')->radioList(backend\modules\vektor\utilities\OptionHandler::render('jenis-sembur'), ['selector'=>'radio', 'inline'=>true]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" >
                                <?= $form->field($model, 'V_KATLOKALITI')->dropDownList(backend\modules\vektor\utilities\OptionHandler::render('kat-lokaliti'), [ 'prompt' => '', 'inline'=>true]); ?> 
                                <?= $form->field($model, 'V_PUSINGAN')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?>                   
                            </div>
                            <div class="col-md-6" >
                                <?= $form->field($model, 'V_ID_SUREVEILAN')->dropDownList(backend\modules\vektor\utilities\OptionHandler::render('ulv-surveilan'), [ 'prompt' => '', 'inline'=>true]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
    </div>
</div>

<!-- Cuaca-->
<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Cuaca') ?>
        </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6" >
                                <?= $form->field($model, 'ULV_HUJAN')->radioList(backend\modules\vektor\utilities\OptionHandler::render('hujan'), ['selector'=>'radio', 'inline'=>true]); ?>
                                <!-- <?= $form->field($model, 'ULV_HUJAN', ['inputOptions' => ["{label}\n<div class=\"col-lg-12\">{input}\n{error}</div>"]]) ?>   -->
                                <?= $form->field($model, 'ULV_KEADAANHUJAN')->dropDownList(backend\modules\vektor\utilities\OptionHandler::render('keadaan-hujan'), [ 'prompt' => '', 'inline'=>true]); ?>
                                <?= $form->field($model, 'ULV_MASAMULAHUJAN')->textInput()->widget(DateTimePicker::className(), [
                                    'name' => 'ULV_MASAMULAHUJAN',
                                    'readonly' => true,
                                    'options' => ['class' => 'custom-datetime-field'],
                                    'pluginOptions' => [
                                        'minuzonamep' => 1,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy, hh:ii',
                                        'autoclose' => true,
                                        'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                    ]
                                ]);?>
                                <?= $form->field($model, 'ULV_MASATAMATHUJAN')->textInput()->widget(DateTimePicker::className(), [
                                    'name' => 'ULV_MASATAMATHUJAN',
                                    'readonly' => true,
                                    'options' => ['class' => 'custom-datetime-field'],
                                    'pluginOptions' => [
                                        'minuzonamep' => 1,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy, hh:ii',
                                        'autoclose' => true,
                                        'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                    ]
                                ]);?>
                            </div>
                            <div class="col-md-6" >
                                <?= $form->field($model, 'ULV_ANGIN')->radioList(backend\modules\vektor\utilities\OptionHandler::render('hujan'), ['selector'=>'radio', 'inline'=>true]); ?>
                                <?= $form->field($model, 'ULV_KEADAANANGIN')->dropDownList(backend\modules\vektor\utilities\OptionHandler::render('keadaan-angin'), [ 'prompt' => '', 'inline'=>true]); ?>
                                <?= $form->field($model, 'ULV_MASAMULAANGIN')->textInput()->widget(DateTimePicker::className(), [
                                    'name' => 'ULV_MASAMULAANGIN',
                                    'readonly' => true,
                                    'options' => ['class' => 'custom-datetime-field'],
                                    'pluginOptions' => [
                                        'minuzonamep' => 1,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy, hh:ii',
                                        'autoclose' => true,
                                        'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                    ]
                                ]);?>
                                <?= $form->field($model, 'ULV_MASATAMATANGIN')->textInput()->widget(DateTimePicker::className(), [
                                    'name' => 'ULV_MASATAMATANGIN',
                                    'readonly' => true,
                                    'options' => ['class' => 'custom-datetime-field'],
                                    'pluginOptions' => [
                                        'minuzonamep' => 1,
                                        'todayHighlight' => true,
                                        'format' => 'dd-mm-yyyy, hh:ii',
                                        'autoclose' => true,
                                        'startDate' => Yii::$app->params['dateTimePicker.startDate'],
                                    ]
                                ]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<!-- Maklumat ULV -->
<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Maklumat ULV') ?>
        </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <!-- nor10112022 -->
                            <div class="col-md-6" >
                                <?= $form->field($model, 'ULV_JENISMESIN')?>
                            </div>
                            <div class="col-md-6" >
                                <?= $form->field($model, 'ULV_BILMESIN')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" >
                                <?= $form->field($model, 'ULV_ID_RACUN')->widget(Select2::className(), [
                                    'initValueText' => $initVal['racun'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'placeholder' => '',
                                        'ajax' => [
                                            'url' => $url['racun'],
                                            'dataType' => 'json',
                                            'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                            'processResults' => new JsExpression('function(data, params) {
                                                params.page = params.page || 1;
                                                return {
                                                    results: data.results,
                                                    pagination: { more: (params.page * 20) < data.total }
                                                };
                                            }'),
                                        ],
                                    ],
                                    // 'options' => ['disabled' => !$model->isNewRecord],
                                    ]) ?>
                            </div>
                            <div class="col-md-6" >
                                <?= $form->field($model, 'ULV_AMAUNRACUN')->textInput(['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->ULV_AMAUNRACUN)]]); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6" >
                                <?= $form->field($model, 'ULV_ID_PELARUT')->widget(Select2::className(), [
                                    'initValueText' => $initVal['pelarut'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'placeholder' => '',
                                        'ajax' => [
                                            'url' => $url['pelarut'],
                                            'dataType' => 'json',
                                            'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                            'processResults' => new JsExpression('function(data, params) {
                                                params.page = params.page || 1;
                                                return {
                                                    results: data.results,
                                                    pagination: { more: (params.page * 20) < data.total }
                                                };
                                            }'),
                                        ],
                                    ],
                                    // 'options' => ['disabled' => !$model->isNewRecord],
                                ]) ?>
                            </div>
                            <div class="col-md-6" >
                                <?= $form->field($model, 'ULV_AMAUNPELARUT')->textInput(['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->ULV_AMAUNPELARUT)]]); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6" >
                                <?= $form->field($model, 'ULV_AMAUNPETROL')->textInput(['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->ULV_AMAUNPETROL)]]) ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
    </div>
</div>
<?php endif; ?>


<!-- ********** Tindakan Penguatkuasaan for PTP ************ -->
<?php if ($model->IDMODULE=="PTP"): ?>
   <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Tindakan Penguatkuasaan') ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">  
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'PTP_JUMKOMPAUN')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?> 
                            </div>

                            <div class="col-md-6">
                                <?= $form->field($model, 'PTP_JUMNOTIS')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- added section for PTP only -nor11112022 -->
<!-- ********** Extra forms for PTP ************ -->
<?php if ($model->IDMODULE=="PTP"): ?>
    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Aktiviti Larvaciding') ?>
            </div>
            <div class="panel-body">
            <h6 style="margin-top:10px;font-weight: bold;"><?= Yii::t('app', 'A. Maklumat Premis Yang Dibubuh Larvisid') ?></h6>
                <div class="row">
                    <div class="col-md-6" style="margin-top:10px;" >
                        <?= $form->field($model, 'V_SASARANPREMIS1')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]); ?> 
                        <?= $form->field($model, 'V_BILBEKAS1')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]); ?> 
                        <!-- <?= $form->field($model, 'V_JUMRACUN1', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->V_JUMRACUN1)]]); ?>     -->
                        <?= $form->field($model, 'V_JUMRACUN1', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->V_JUMRACUN1)]]); ?>
   
                    </div>
                    <div class="col-md-6"  style="margin-top:10px;">
                        <?= $form->field($model, 'V_BILPREMIS1')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?> 
                        <?= $form->field($model, 'V_ID_JENISRACUN1')->widget(Select2::className(), [
                            'initValueText' => $initVal['racun'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => '',
                                'ajax' => [
                                    'url' => $url['racun'],
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                    'processResults' => new JsExpression('function(data, params) {
                                        params.page = params.page || 1;
                                        return {
                                            results: data.results,
                                            pagination: { more: (params.page * 20) < data.total }
                                        };
                                    }'),
                                ],
                            ],
                            // 'options' => ['disabled' => !$model->isNewRecord],
                            ]) ?>
                    </div>
                </div>

                <div style="margin-top:25px;">
                    <h6 style="font-weight: bold;"><?= Yii::t('app', 'B. Maklumat Premis Yang Disembur Larvisid') ?></h6>
                    <div class="row">
                        <div class="col-md-6" style="margin-top:10px;" >
                            <?= $form->field($model, 'V_SASARANPREMIS2')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]); ?> 
                            <?= $form->field($model, 'V_BILBEKAS2')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]); ?> 
                            <!-- <?= $form->field($model, 'V_JUMRACUN2', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->V_JUMRACUN1)]]); ?>     -->
                            <?= $form->field($model, 'V_JUMRACUN2', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->V_JUMRACUN1)]]); ?>
    
                        </div>
                        <div class="col-md-6"  style="margin-top:10px;">
                            <?= $form->field($model, 'V_BILPREMIS2')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?> 
                            <?= $form->field($model, 'V_ID_JENISRACUN2')->widget(Select2::className(), [
                                // 'initValueText' => $initVal['racun'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'placeholder' => '',
                                    'ajax' => [
                                        'url' => $url['racun'],
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                        'processResults' => new JsExpression('function(data, params) {
                                            params.page = params.page || 1;
                                            return {
                                                results: data.results,
                                                pagination: { more: (params.page * 20) < data.total }
                                            };
                                        }'),
                                    ],
                                ],
                                // 'options' => ['disabled' => !$model->isNewRecord],
                                ]) ?>
                        </div>
                    </div>
                </div>

                <div style="margin-top:25px;">
                    <h6 style="font-weight: bold;"><?= Yii::t('app', 'C. Agihan Racun Larvisid') ?></h6>
                    <div class="row">
                        <div class="col-md-6" style="margin-top:10px;" >
                            <?= $form->field($model, 'V_BILORANG')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]); ?> 
                            <?= $form->field($model, 'V_ID_JENISRACUN3')->widget(Select2::className(), [
                                // 'initValueText' => $initVal['racun'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'placeholder' => '',
                                    'ajax' => [
                                        'url' => $url['racun'],
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                        'processResults' => new JsExpression('function(data, params) {
                                            params.page = params.page || 1;
                                            return {
                                                results: data.results,
                                                pagination: { more: (params.page * 20) < data.total }
                                            };
                                        }'),
                                    ],
                                ],
                                // 'options' => ['disabled' => !$model->isNewRecord],
                                ]) ?> 
                            <?= $form->field($model, 'PTP_NAMAKK')->textInput(); ?>                                
                        </div>
                        <div class="col-md-6"  style="margin-top:10px;">
                            <?= $form->field($model, 'V_BILPREMIS3')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?> 
                            <?= $form->field($model, 'V_JUMRACUN3', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->V_JUMRACUN1)]]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- split section for LVC only -nor11112022 --> 
<!-- ********** Extra forms for LVC ************ -->
<?php if ($model->IDMODULE=="LVC"): ?>
    <!-- AKTIVITI LARVACIDING -->
    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Aktiviti Larvaciding') ?>
            </div>
            <div class="panel-body">
            <h6 style="margin-top:10px;font-weight: bold;"><?= Yii::t('app', 'A. Maklumat Premis Disembur Larvisid - Spraycan') ?></h6>
                <div class="row">
                    <div class="col-md-6" style="margin-top:10px;" >
                        <?= $form->field($model, 'V_SASARANPREMIS1')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]); ?> 
                        <?= $form->field($model, 'V_BILBEKAS1')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]); ?> 
                        <!-- <?= $form->field($model, 'V_JUMRACUN1', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->V_JUMRACUN1)]]); ?>     -->
                        <?= $form->field($model, 'V_JUMRACUN1', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->V_JUMRACUN1)]]); ?>
   
                    </div>
                    <div class="col-md-6"  style="margin-top:10px;">
                        <?= $form->field($model, 'V_BILPREMIS1')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?> 
                        <?= $form->field($model, 'V_ID_JENISRACUN1')->widget(Select2::className(), [
                            'initValueText' => $initVal['racun'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => '',
                                'ajax' => [
                                    'url' => $url['racun'],
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                    'processResults' => new JsExpression('function(data, params) {
                                        params.page = params.page || 1;
                                        return {
                                            results: data.results,
                                            pagination: { more: (params.page * 20) < data.total }
                                        };
                                    }'),
                                ],
                            ],
                            // 'options' => ['disabled' => !$model->isNewRecord],
                            ]) ?>
                            <?= $form->field($model, 'V_BILMESIN1')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?> 
                    </div>
                </div>

                <div style="margin-top:25px;">
                    <h6 style="font-weight: bold;"><?= Yii::t('app', 'B. Maklumat Premis Disembur Larvisid - Mistblower') ?></h6>
                    <div class="row">
                        <div class="col-md-6" style="margin-top:10px;" >
                            <?= $form->field($model, 'V_SASARANPREMIS2')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]); ?> 
                            <?= $form->field($model, 'V_BILBEKAS2')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]); ?> 
                            <?= $form->field($model, 'V_JUMRACUN2', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->V_JUMRACUN2)]]); ?>    
                        </div>
                        <div class="col-md-6"  style="margin-top:10px;">
                            <?= $form->field($model, 'V_BILPREMIS2')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?> 
                            <?= $form->field($model, 'V_ID_JENISRACUN2')->widget(Select2::className(), [
                                // 'initValueText' => $initVal['racun'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'placeholder' => '',
                                    'ajax' => [
                                        'url' => $url['racun'],
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                        'processResults' => new JsExpression('function(data, params) {
                                            params.page = params.page || 1;
                                            return {
                                                results: data.results,
                                                pagination: { more: (params.page * 20) < data.total }
                                            };
                                        }'),
                                    ],
                                ],
                                // 'options' => ['disabled' => !$model->isNewRecord],
                                ]) ?>
                                <?= $form->field($model, 'V_BILMESIN2')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- split section for LVC and PTP only -nor11112022 --> 
<?php if ($model->IDMODULE=="LVC" || $model->IDMODULE=="PTP"): ?>
    <!-- maklumat tujuan aktiviti -->
    <div class="report-master-search">
        <div class="panel panel-default no-print">
            <div class="panel-heading">
                <?= Yii::t('app', 'Tujuan Aktiviti') ?>
            </div>
            <div class="panel-body">
                <h6 style="font-weight: bold;"><?= Yii::t('app', 'A. Jenis Pemeriksaan') ?></h6>
                <div class="row">
                    <div class="col-md-6" >
                        <?= $form->field($model, 'V_JENISSEMBUR')->radioList(backend\modules\vektor\utilities\OptionHandler::render('jenis-sembur'), ['selector'=>'radio', 'inline'=>true])->label(false); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" >
                        <?= $form->field($model, 'V_KATLOKALITI')->dropDownList(backend\modules\vektor\utilities\OptionHandler::render('kat-lokaliti'), [ 'prompt' => '', 'inline'=>true]); ?> 
                        <?= $form->field($model, 'V_PUSINGAN')->textInput(['type' => 'number', 'min' => ($model->isNewRecord ? 1 : 0)]) ?>                   
                    </div>
                    <div class="col-md-6" >
                        <?= $form->field($model, 'V_ID_SUREVEILAN')->dropDownList(backend\modules\vektor\utilities\OptionHandler::render('ptp-surveilan'), [ 'prompt' => '', 'inline'=>true]); ?>
                    </div>
                </div>

                <div style="margin-top:25px;">
                    <h6 style="font-weight: bold;"><?= Yii::t('app', 'B. Tempoh Masa Pemeriksaan Selepas Notifikasi Kes') ?></h6>
                    <div class="row">
                        <div class="col-md-6" style="margin-top:10px;" >
                            <?= $form->field($model, 'V_TEMPOH')->radioList(backend\modules\vektor\utilities\OptionHandler::render('tempoh'), ['selector'=>'radio', 'inline'=>true])->label(false); ?>
                        </div>
                        <div class="col-md-6" style="margin-top:10px;">
                            <?= $form->field($model, 'V_ID_ALASAN')->widget(Select2::className(), [
                                'initValueText' => $initVal['alasan'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'placeholder' => '',
                                    'ajax' => [
                                        'url' => $url['alasan'],
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                        'processResults' => new JsExpression('function(data, params) {
                                            params.page = params.page || 1;
                                            return {
                                                results: data.results,
                                                pagination: { more: (params.page * 20) < data.total }
                                            };
                                        }'),
                                    ],
                                ],
                                // 'options' => ['disabled' => !$model->isNewRecord],
                                ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- ==================================================== End Pencegahan Vektor ========================================================== -->


<!-- Pegawai Pemeriksa forms-->
<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Maklumat Pasukan/Pegawai Operasi') ?>
        </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'KETUAPASUKAN')->widget(Select2::className(), [
                                    'data' => $data['KETUAPASUKAN'],
                                    'options' => [
                                        'placeholder' => '',
                                        // 'disabled' => $model->isNewRecord ? false : true,
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'placeholder' => '',
                                        'ajax' => [
                                            'url' => $url['KETUAPASUKAN'],
                                            'dataType' => 'json',
                                            'data' => new JsExpression('function(params) { return {search:params.term, page:params.page}; }'),
                                            'processResults' => new JsExpression('function(data, params) {
                                                params.page = params.page || 1;
                                                return {
                                                    results: data.results,
                                                    pagination: { more: (params.page * 20) < data.total }
                                                };
                                            }'),
                                        ],
                                    ],
                                ]) ?>

                                <?= $form->field($model, 'ahlipasukan')->dropDownList([], [
                                        'multiple' => true,
                                    ]) ?>

                                <?= $form->field($model, '_inputahlipasukan')->hiddenInput()->label(false) ?>
                            </div>
                    </div>
                </div>
         </div>
    </div>
</div>

<?php if ($model->IDMODULE!="SRT" && $model->IDMODULE!="ULV" && $model->IDMODULE!="PTP" && $model->IDMODULE!="LVC" && $model->IDMODULE!="KPN"): ?>
<!-- section penerima -->
<div class="report-master-search">
    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <?= Yii::t('app', 'Maklumat Penerima') ?>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">  
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'NAMAPENERIMA')?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'NOKPPENERIMA')?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



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

<?php
$this->registerCssFile(Yii::getAlias('@web/css/multi.css'));
$this->registerJsFile(Yii::getAlias('@web/js/multi_faeezrev_v2.js'), ['depends' => 'yii\web\YiiAsset']);

//untuk ahli pasukan
$this->registerJs("
var _tmpMultiSelect;

// hold jenis ahli value
var jenisahli = null;

// hold selected ahlipasukan option elements
var selectedAhli = [];

// hold selected ahlipasukan value
var ahliValues = [];

getPengguna();

function myFunction() {
    var checkBox = document.getElementById('ptm_istandas');
    var text = document.getElementById('');
    if (checkBox.checked == true){
      text.style.display = 'block';
    } else {
       text.style.display = 'none';
    }
  }

function getPengguna()
{
    $.get('" . Url::to(['/option/penyelenggaraan/list']) . "', function (data) {

        if (data.success) {
            selectedAhli = $('#lawatanmain-ahlipasukan option:selected').toArray();

            ahliValues = $('#lawatanmain-ahlipasukan option:selected').map(function() {
                return this.value;
            }).get();

            $('#lawatanmain-ahlipasukan')[0].options.length = 0;

            $.each(selectedAhli, function(key, item) {
                $('#lawatanmain-ahlipasukan').append(item);
            });

            $.each(data.results, function(key, item) {

                if ( (jQuery.inArray(key, ahliValues) == -1) ) {
                    var o = new Option(item, key);

                    $(o).html(item);
                    $('#lawatanmain-ahlipasukan').append(o);
                }
            });

            $('#lawatanmain-ahlipasukan').trigger('change');
        }
    });
}

$(document).ready(function () {
    $('#lawatanmain-form').submit(function(event){
        $('#lawatanmain-_inputahlipasukan').val(_tmpMultiSelect);
        return true;
    });


    // $('#lawatanmain-ptm_istandas).
    // (this.value == 1) {
    //     $('#divTandas').show();
    // } else {
    //     $('#divTandas').hide();
    // }
});
", View::POS_END);


if (!$model->isNewRecord) {

    $this->registerJs("
    // hold value to see if ahlipasukan attribute has value
    var ahliRecords = [];

    $(document).ready(function () {
        // get ahliRecords list using ajax
        $.get('" . Url::to(['/lawatanmain/lawatan-main/get-ahlis'])  . "', { NOSIRI: '". $model->NOSIRI ."' }, function (data) {
            if (data) {
                ahliRecords = data;
            }
        });

        // convert ahlipasukan into string
        var ahlipasukan = '" . implode(',', $model->ahlipasukan) . "';

        // convert string ahlipasukan into array for js
        var tmpPasukanData = ahlipasukan.split(',');

        // predefinded variable
        var arrData = [];
        var arrDataInit = [];

        // loop ahlipasukan array
        $.each(tmpPasukanData, function (i, val) {
            // array for selected ahlipasukan dropdown list
            arrData[i] = val;
            
            // array for hidden selected ahlipasukan dropdown list
            arrDataInit[i] = parseInt(val);   
        });

        _tmpMultiSelect = arrData;
     
        // setup ahlipasukan dropdown field html and configs
        $('#lawatanmain-ahlipasukan').multi({
            search_placeholder: 'Cari...',
            compare: 'lawatanmain-ketuapasukan',
            multiSelected: arrData,
            compareErrorText: '" . Yii::t('app', 'Ketua pasukan tidak boleh berada di dalam ahli pasukan') . "'
        });

        window.setTimeout(function () {            
            $.each(ahliRecords, function(key, item) {
                if ($(\"#lawatanmain-ahlipasukan option[value=\" + key +\"]\").length < 1) {
                    var o = new Option(item, key);
                    $(o).html(item);
                    $('#lawatanmain-ahlipasukan').append(o);
                }
            });
            $('#lawatanmain-ahlipasukan').val(arrDataInit).trigger('change');
        }, 500);
        
    });
    ", View::POS_END);
} else {
    $this->registerJs("
    $(document).ready(function () {
        $('#lawatanmain-ahlipasukan').multi({
            search_placeholder: 'Cari...',
            compare: 'lawatanmain-ketuapasukan',
            compareErrorText: '" . Yii::t('app', 'Ketua pasukan tidak boleh berada di dalam ahli pasukan') . "'
        });
    });
    ", View::POS_END);
}
