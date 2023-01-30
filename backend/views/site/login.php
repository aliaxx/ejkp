<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Login');
?>
<style>
.navbar {display:none;}
.wrap > .container {padding-top:0;}
.container input {padding:20px 15px;font-size:14px;}
.container .btn {font-size:16px;padding:11px 0;text-transform:uppercase;}
.container .help-block {font-size:14px;color:#d16664;}
.wrap {background:url(../images/background.jpg);background-size:cover;}

</style>
<div>
    <div class="iems-login-container">
        <div class="iems-login-title">
            <div>
                <?= Html::img('@web/images/logo.png') ?>
            </div>
            <div>Daftar Masuk</div>
            <div> Sistem Jabatan Perkhidmatan dan Persekitaran (eJKP)
            <br>Majlis Bandaraya Petaling Jaya</div>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['autocomplete' => 'off'],
            'fieldConfig' => ['template' => "{input}\n{error}"],
        ]); ?>

            <?= $form->field($model, 'username')->textInput([
                'autofocus' => true, 'maxlength' => true, 'placeholder' => $model->getAttributeLabel('username'),
            ]) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'maxlength' => true, 'placeholder' => $model->getAttributeLabel('password'),
            ]) ?>

            <!-- <?= $form->field($model, 'rememberMe')->checkbox() ?> -->

            <div class="form-group">
                <?= Html::submitButton('Log Masuk', ['class' => 'btn btn-primary', 'name' => 'login-button', 'style' => 'width:100%']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<!--<div class="iesm-login-footer">Hak Cipta Terpelihara 2019 | Majlis Bandaraya Petaling Jaya | Versi 1.0.0</div>-->
