<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * SideNav assets
 */
class SideNavAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/sidenav.css',
        'css/mCustomScrollbar.min.css',
    ];
    public $js = [
        'js/sidenav.js',
        'js/mCustomScrollbar.concat.min.js',
    ];
    public $depends = [];
}
