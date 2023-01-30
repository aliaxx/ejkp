<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\premis\models\PenggredanPremisSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penggredan-premis-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'NOSIRI') ?>

    <?= $form->field($model, 'IDMODULE') ?>

    <?= $form->field($model, 'JENISPREMIS') ?>

    <?= $form->field($model, 'IDZON_AM') ?>

    <?php // echo $form->field($model, 'PRGNLOKASI_AM') ?>

    <?php // echo $form->field($model, 'PRGNLOKASI') ?>

    <?php // echo $form->field($model, 'IDDUN') ?>

    <?php // echo $form->field($model, 'ID_TUJUAN') ?>

    <?php // echo $form->field($model, 'IDSUBUNIT') ?>

    <?php // echo $form->field($model, 'TRKHMULA') ?>

    <?php // echo $form->field($model, 'TRKHTAMAT') ?>

    <?php // echo $form->field($model, 'NOADUAN') ?>

    <?php // echo $form->field($model, 'IDLOKASI') ?>

    <?php // echo $form->field($model, 'LATITUD') ?>

    <?php // echo $form->field($model, 'LONGITUD') ?>

    <?php // echo $form->field($model, 'CATATAN') ?>

    <?php // echo $form->field($model, 'STATUS') ?>

    <?php // echo $form->field($model, 'STATUSREKOD') ?>

    <?php // echo $form->field($model, 'PGNDAFTAR') ?>

    <?php // echo $form->field($model, 'TRKHDAFTAR') ?>

    <?php // echo $form->field($model, 'PGNAKHIR') ?>

    <?php // echo $form->field($model, 'TRKHAKHIR') ?>

    <?php // echo $form->field($model, 'PP_PENGELUAR') ?>

    <?php // echo $form->field($model, 'PP_BILPENGENDALI') ?>

    <?php // echo $form->field($model, 'PP_SUNTIKAN_ANTITIFOID') ?>

    <?php // echo $form->field($model, 'PP_KURSUS_PENGENDALI') ?>

    <?php // echo $form->field($model, 'SMM_ID_JENISSAMPEL') ?>

    <?php // echo $form->field($model, 'SI_ID_STOR') ?>

    <?php // echo $form->field($model, 'PK_NAMAPENYELIA') ?>

    <?php // echo $form->field($model, 'PK_NOKPPENYELIA') ?>

    <?php // echo $form->field($model, 'PK_JENISRAWATAN') ?>

    <?php // echo $form->field($model, 'PK_JENISKOLAM') ?>

    <?php // echo $form->field($model, 'PK_JUMPENGGUNA') ?>

    <?php // echo $form->field($model, 'ST_NOWABAK') ?>

    <?php // echo $form->field($model, 'ST_NOAKTIVITI') ?>

    <?php // echo $form->field($model, 'ST_RUJUKANKES') ?>

    <?php // echo $form->field($model, 'ST_ID_SEMBURANSRT') ?>

    <?php // echo $form->field($model, 'ST_TLENGKAPDALAM') ?>

    <?php // echo $form->field($model, 'ST_TLENGKAPLUAR') ?>

    <?php // echo $form->field($model, 'ST_LENGKAP') ?>

    <?php // echo $form->field($model, 'ST_TPERIKSA') ?>

    <?php // echo $form->field($model, 'ST_BILPENDUDUK') ?>

    <?php // echo $form->field($model, 'ST_SB1') ?>

    <?php // echo $form->field($model, 'ST_SB2') ?>

    <?php // echo $form->field($model, 'ST_SB3') ?>

    <?php // echo $form->field($model, 'ST_SB4') ?>

    <?php // echo $form->field($model, 'UV_PA_NODAFTARKES') ?>

    <?php // echo $form->field($model, 'UV_PA_LOKALITI') ?>

    <?php // echo $form->field($model, 'UV_PA_KAWASAN') ?>

    <?php // echo $form->field($model, 'UV_TRKHONSET') ?>

    <?php // echo $form->field($model, 'UV_TRKHKEYIN') ?>

    <?php // echo $form->field($model, 'UV_TRKHNOTIFIKASI') ?>

    <?php // echo $form->field($model, 'UV_PA_JENISSEMBUR') ?>

    <?php // echo $form->field($model, 'UV_PA_KATLOKALITI') ?>

    <?php // echo $form->field($model, 'UV_PA_PUSINGAN') ?>

    <?php // echo $form->field($model, 'UV_PA_ID_SUREVEILAN') ?>

    <?php // echo $form->field($model, 'UV_HUJAN') ?>

    <?php // echo $form->field($model, 'UV_KEADAANHUJAN') ?>

    <?php // echo $form->field($model, 'UV_MASAMULAHUJAN') ?>

    <?php // echo $form->field($model, 'UV_MASATAMATHUJAN') ?>

    <?php // echo $form->field($model, 'UV_ANGIN') ?>

    <?php // echo $form->field($model, 'UV_KEADAANANGIN') ?>

    <?php // echo $form->field($model, 'UV_MASAMULAANGIN') ?>

    <?php // echo $form->field($model, 'UV_MASATAMATANGIN') ?>

    <?php // echo $form->field($model, 'UV_JENISMESIN') ?>

    <?php // echo $form->field($model, 'UV_BILMESIN') ?>

    <?php // echo $form->field($model, 'UV_ID_RACUN') ?>

    <?php // echo $form->field($model, 'UV_AMAUNRACUN') ?>

    <?php // echo $form->field($model, 'UV_ID_PELARUT') ?>

    <?php // echo $form->field($model, 'UV_AMAUNPELARUT') ?>

    <?php // echo $form->field($model, 'UV_AMAUNPETROL') ?>

    <?php // echo $form->field($model, 'PA_TRKHKEYINEDENGGI') ?>

    <?php // echo $form->field($model, 'PA_TRKHNOTIFIKASI') ?>

    <?php // echo $form->field($model, 'PA_MINGGUEPID') ?>

    <?php // echo $form->field($model, 'PA_SASARANPREMIS1') ?>

    <?php // echo $form->field($model, 'PA_BILPREMIS1') ?>

    <?php // echo $form->field($model, 'PA_BILBEKAS1') ?>

    <?php // echo $form->field($model, 'PA_ID_JENISRACUN1') ?>

    <?php // echo $form->field($model, 'PA_JUMRACUN1') ?>

    <?php // echo $form->field($model, 'PA_SASARANPREMIS2') ?>

    <?php // echo $form->field($model, 'PA_BILPREMIS2') ?>

    <?php // echo $form->field($model, 'PA_BILBEKAS2') ?>

    <?php // echo $form->field($model, 'PA_ID_JENISRACUN2') ?>

    <?php // echo $form->field($model, 'PA_JUMRACUN2') ?>

    <?php // echo $form->field($model, 'PA_BILORANG') ?>

    <?php // echo $form->field($model, 'PA_BILPREMIS3') ?>

    <?php // echo $form->field($model, 'PA_ID_JENISRACUN3') ?>

    <?php // echo $form->field($model, 'PA_JUMRACUN3') ?>

    <?php // echo $form->field($model, 'PA_NAMAKK') ?>

    <?php // echo $form->field($model, 'PA_TEMPOH') ?>

    <?php // echo $form->field($model, 'PA_ID_ALASAN') ?>

    <?php // echo $form->field($model, 'PA_BILPENDUDUK') ?>

    <?php // echo $form->field($model, 'PA_BILBEKASMUSNAH') ?>

    <?php // echo $form->field($model, 'PA_TLENGKAPDALAM') ?>

    <?php // echo $form->field($model, 'PA_TLENGKAPLUAR') ?>

    <?php // echo $form->field($model, 'PA_LENGKAP') ?>

    <?php // echo $form->field($model, 'PA_TPERIKSA') ?>

    <?php // echo $form->field($model, 'PA_JUMSEBAB1') ?>

    <?php // echo $form->field($model, 'PA_JUMSEBAB2') ?>

    <?php // echo $form->field($model, 'PA_JUMSEBAB3') ?>

    <?php // echo $form->field($model, 'PA_JUMSEBAB4') ?>

    <?php // echo $form->field($model, 'PT_ID_JENISPREMISTANDAS') ?>

    <?php // echo $form->field($model, 'NAMAPENERIMA') ?>

    <?php // echo $form->field($model, 'NOKPPENERIMA') ?>

    <?php // echo $form->field($model, 'JUMKOMPAUN') ?>

    <?php // echo $form->field($model, 'JUMNOTIS') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
