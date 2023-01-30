<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Dashboard';
?>

    <div style="margin-bottom:15px;padding:0 15px;">
        Selamat Datang <b><?= Yii::$app->user->identity->NAMA ?></b>

        <div style="margin-top:15px;">
            <?= $this->render('charts/_card') ?>
        </div> 

        <div style="margin-top:15px;">
            <?= $this->render('charts/_peniaga') ?>
        </div>

        <div style="margin-top:15px;">
            <a target="_blank" href="<?= Url::toRoute(['/site/map']) ?>" class="btn btn-info">Lihat Paparan Lokasi</a>
        </div>

        <!-- <div style="margin-top:15px;">
            <?= Url::toRoute(['/site/map']) ?>
        </div>  -->



    </div>