<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
// use backend\modules\penyelenggaraan\models\ParamDetail;

$logo = Html::img('@web/images/logo.png', ['style' => 'height:40px;position:relative;top:-4px;']);
// $logo = Html::img('@web/images/', ['style' => 'height:40px;position:relative;top:-4px;']);
$header = Html::a($logo.' <span style="margin-left:10px;">'.Yii::$app->name, Url::home()).'</span>'; //display logo & eJKP -NOR26082022
if (!Yii::$app->user->isGuest) {
    $header .= '<a href="javascript:toggleSideNav()" style="margin-left:20px;"><i class="fa fa-bars"></i></a>';
}

NavBar::begin([
    'headerContent' => '<div class="navbar-header-group">'.$header.'</div>',
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

//display peranan 
$peranan = '';
if (!is_null(Yii::$app->user->id)){
    $pengguna = \common\models\Pengguna::findOne(['ID' => Yii::$app->user->ID]);
    //$peranan = $pengguna->peranan0->NAMAPERANAN;
    $peranan= (!empty($pengguna->peranan0->NAMAPERANAN))? $pengguna->peranan0->NAMAPERANAN: null;
}

// var_dump($unit);
// exit();

//display menu in profil dropdown
$menuItems = [];
$menu['account'] = [
    ['label' => '<b>'.$peranan.'</b>'], 
    ['label' => 'Log Keluar', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
    // ['label' => 'Manual Pengguna Aplikasi', 'url' => ['/document/Manual App.pdf'], 'linkOptions' => array('target' => '_blank')],
    // ['label' => 'Manual Pengguna Sistem', 'url' => ['/document/Manual Sistem.pdf'], 'linkOptions' => array('target' => '_blank')],
    // ['label' => 'Q&A IEMS', 'url' => ['/document/QAIEMS.pdf'], 'linkOptions' => array('target' => '_blank')],    
    // ['label' => 'GPPK 2020', 'url' => ['/document/GPPK 2020.pdf'], 'linkOptions' => array('target' => '_blank')],    
];

if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Log Masuk', 'url' => ['/site/login']];
} else {
    $user = Yii::$app->user->identity;
    $label = '<b>'.mb_strimwidth($user->NAMA, 0, 50, '...').'</b>';
    if (isset($user->subunit0)) {
        $label .= '<br />'.$user->subunit0->PRGN.', '.$user->unit->PRGN; //to display subunit, unit under username -NOR26082022
    }

    $menuItems[] = ['label' => '<div class="nav-user">'.$label.'</div>', 'items' => $menu['account']];
}
echo Nav::widget([
    'encodeLabels' => false,
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();
