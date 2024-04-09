<?php

namespace noam148\imagemanager\assets;

use yii\web\AssetBundle;

/**
 * ImageManagerInputAsset.
 */
class ImageManagerInputAsset extends AssetBundle
{

    public $sourcePath = '@vendor/noam148/yii2-image-manager/assets/source';

    public $css = [
        'css/imagemanager.input.css',
        'css/dashboard.2a01f7e4.css',
        //        'css/main.0bf01421.css',
        //        'css/pages.f7a945f6.css'
    ];

    public $js = [
        'js/script.imagemanager.input.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        //        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
