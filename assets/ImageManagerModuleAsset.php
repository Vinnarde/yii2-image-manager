<?php

namespace noam148\imagemanager\assets;

use yii\web\AssetBundle;

/**
 * ImageManagerModuleAsset.
 */
class ImageManagerModuleAsset extends AssetBundle
{
    public $sourcePath = '@vendor/noam148/yii2-image-manager/assets/source';
    public $css        = [
        'css/cropper.min.css',
        'css/imagemanager.module.css',
        'css/dashboard.2a01f7e4.css',
        //        'css/main.0bf01421.css',
        //        'css/pages.f7a945f6.css'
    ];
    public $js         = [
        'js/cropper.min.js',
        'js/script.imagemanager.module.js',
    ];
    public $depends    = [
        'yii\web\JqueryAsset',
        //        'yii\bootstrap\BootstrapPluginAsset',
    ];
}