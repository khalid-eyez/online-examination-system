<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
        'css/adminlte.min.css',
        'plugins/fontawesome-free/css/all.min.css',
    
    ];
    public $js = [
   
        'plugins/bootstrap/js/bootstrap.bundle.min.js',
        'js/adminlte.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
