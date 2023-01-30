<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AutoNumericAsset extends AssetBundle
{
    public $sourcePath = '@bower/autoNumeric';
    public $js = [
        'autoNumeric.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
