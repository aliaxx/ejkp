<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Common assets for backend and frontend interfaces
 */
class CommonAsset extends AssetBundle
{
    public $sourcePath = '@backend/web';
    public $css = [
        'css/codetitan.common.css',
    ];
    public $js = [];
    public $depends = [];
}
