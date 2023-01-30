<?php
return [
    'name' => 'eJKP',
    'language' => 'ms-MY',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [//schema ejkp
            'class' => 'yii\db\Connection',
            'tablePrefix' => 'TB',
            'enableSchemaCache' => true,
            
        ],
        'db2' => [//schema elesen
            'class' => 'yii\db\Connection',
            'dsn' => 'oci:dbname=//localhost:1521/orcl;charset=UTF8', //make sure dekat xampp/php/php.ini dah enable pdo:oci untuk access webpage.     
            'username' => 'C##ELESEN',
            'password' => '123456',
            'on afterOpen' => function ($event) {
                $event->sender->createCommand("ALTER SESSION SET NLS_TIME_FORMAT = 'HH24:MI:SS' NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS TZH:TZM'")->execute();
            }
        ],
        'db3' => [//schema majlis
            'class' => 'yii\db\Connection',
            'dsn' => 'oci:dbname=//localhost:1521/orcl;charset=UTF8', //make sure dekat xampp/php/php.ini dah enable pdo:oci untuk access webpage.     
            'username' => 'C##MAJLIS',
            'password' => '123456',
            'on afterOpen' => function ($event) {
                $event->sender->createCommand("ALTER SESSION SET NLS_TIME_FORMAT = 'HH24:MI:SS' NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS TZH:TZM'")->execute();
            }
        ],
        'db4' => [//schema ahlimajlis
            'class' => 'yii\db\Connection',
            'dsn' => 'oci:dbname=//localhost:1521/orcl;charset=UTF8', //make sure dekat xampp/php/php.ini dah enable pdo:oci untuk access webpage.     
            'username' => 'C##AHLIMAJLIS',
            'password' => '123456',
            'on afterOpen' => function ($event) {
                $event->sender->createCommand("ALTER SESSION SET NLS_TIME_FORMAT = 'HH24:MI:SS' NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS TZH:TZM'")->execute();
            }
        ],
        'db5' => [//schema esewa
            'class' => 'yii\db\Connection',
            'dsn' => 'oci:dbname=//localhost:1521/orcl;charset=UTF8', //make sure dekat xampp/php/php.ini dah enable pdo:oci untuk access webpage.     
            'username' => 'C##ESEWA',
            'password' => '123456',
            'on afterOpen' => function ($event) {
                $event->sender->createCommand("ALTER SESSION SET NLS_TIME_FORMAT = 'HH24:MI:SS' NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS TZH:TZM'")->execute();
            }
        ],
        'db6' => [//schema sppj
            'class' => 'yii\db\Connection',
            'dsn' => 'oci:dbname=//localhost:1521/orcl;charset=UTF8', //make sure dekat xampp/php/php.ini dah enable pdo:oci untuk access webpage.     
            'username' => 'C##SPPJ',
            'password' => '123456',
            'on afterOpen' => function ($event) {
                $event->sender->createCommand("ALTER SESSION SET NLS_TIME_FORMAT = 'HH24:MI:SS' NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS TZH:TZM'")->execute();
            }
        ],
        'session' => [
            //'class' => 'yii\web\DbSession',           //ZIHAN 20220715 DISABLE TEMPORARY
        ],
        'assetManager' => [
            'bundles' => [
                'kartik\icons\FontAwesomeAsset' => [
                    'css' => ['https://use.fontawesome.com/releases/v5.3.1/css/all.css'],
                    'js' => [],
                    'cssOptions' => ['crossorigin' => 'anonymous'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'dd/MM/yyyy',
            'datetimeFormat' => 'dd/MM/yyyy, HH:mm',
            'timeFormat' => 'HH:mm',
            'nullDisplay' => ''
        ],
        'i18n' => [
            'translations' => [
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],
    ],
    'params' => [
        'icon-framework' => 'fa',
    ],
    
    'aliases' => [
		'@bower' => '@vendor/bower-asset',
    ],
];
