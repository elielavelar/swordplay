<?php
namespace frontend\assets;
use yii\web\AssetBundle;

/**
 * Description of MomentTimerAsset
 *
 * @author avelare
 */
class MomentTimerAsset extends AssetBundle {
    public $sourcePath = '@bower/moment-timer';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'lib/moment-timer.js',
    ];
}