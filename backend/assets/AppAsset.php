<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $sourcePath = '@app/assets/bootstrap3/';
    public $css = [
        'css/custom.css',
        'css/docs.min.css',
    ];
    public $js = [
        'js/jquery-migrate-3.0.0.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'omnilight\assets\SweetAlertAsset',
        'backend\assets\FontAwesomeAsset',
        'webtoolsnz\AdminLte\AdminLteAsset',
        'backend\assets\MomentAsset',
        'backend\assets\MomentTimerAsset',
    ];
}
