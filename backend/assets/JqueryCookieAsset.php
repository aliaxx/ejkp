<?php

namespace backend\assets;

use yii\web\AssetBundle;

class JqueryCookieAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-cookie';
    public $js = [
        'src/jquery.cookie.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
