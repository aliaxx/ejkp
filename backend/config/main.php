<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'settings'],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'settings' => [
            'class' => 'app\components\Settings',
        ],
        'access' => [
            'class' => 'app\components\Access',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'rules' => [
                'pengguna' => 'user/index',
                'pengguna/<action:\w+>' => 'user/<action>',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [ //SETTING FOR MATERIAL DASHBOARD THEME
            'bundles' => [
                'deyraka\materialdashboard\web\MaterialDashboardAsset',
            ],
        ],
        
    ],
    // **
    //Nama exist folder module module yang terlibat...
    //Selain tu kena declare dekat module.php di setiap module yang dicreate. 
    //Supaya nama file dapat dibaca semasa letak path di sidenav.
    //**
    'modules' => [
        'option' => ['class' => 'backend\modules\option\Module'],
        'lookup' => ['class' => 'backend\modules\lookup\Module'],
        'penyelenggaraan' => ['class' => 'backend\modules\penyelenggaraan\Module'],
        'integrasi' => ['class' => 'backend\modules\integrasi\Module'],
        'vektor' => ['class' => 'backend\modules\vektor\Module'],
        'peniaga' => ['class' => 'backend\modules\peniaga\Module'],
        'pengguna' => ['class' => '\backend\modules\pengguna\Module'],
        'makanan' => ['class' => '\backend\modules\makanan\Module'],
        'premis' => ['class' => '\backend\modules\premis\Module'],
        'lawatanmain' => ['class' => '\backend\modules\lawatanmain\Module'],
    ],
    'params' => $params,
];
