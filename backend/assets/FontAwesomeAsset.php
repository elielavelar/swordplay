<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Description of FontAwesomeAsset
 *
 * @author ghost
 */
class FontAwesomeAsset extends AssetBundle {
    // The files are not web directory accessible, therefore we need 
 // to specify the sourcePath property. Notice the @vendor alias used.
    public $sourcePath = '@vendor/components/font-awesome';
    public $css = [
        'css/fontawesome-all.css',
    ];
}
