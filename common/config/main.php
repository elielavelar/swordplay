<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'America/El_Salvador',
    'version' => '1.0.0',
    'language'=>'es-SV',
    'modules'=>[
        'gridview'=>[
            'class'=>'\kartik\grid\Module',
        ],
        'backup' => [
            'class' => 'backend\modules\admin\Backup',
            'defaultRoute'=>'backup',
            'params'=>[
                'backupPath'=>'/web/_backup',
                'backupUrl'=>'_backup',
            ],
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'defaultTimeZone' => 'America/El_Salvador',
            #'dateFormat' => 'php:yyyy-mm-dd',
            #'datetimeFormat' => 'php:yyyy-mm-dd H:i',
            'dateFormat' => 'php:d-m-Y',
            'datetimeFormat' => 'php:d-m-Y H:i',
            'timeFormat' => 'php:h:i:s',
            'decimalSeparator' => '.',
            'thousandSeparator' => ',',
            'currencyCode' => 'USD',
//            'numberFormatterSymbols'=>[
//                NumberFormatter::CURRENCY_SYMBOL => '$',
//            ],
//            'numberFormatterOptions'=>[
//                NumberFormatter::MAX_FRACTION_DIGITS => 6,
//                NumberFormatter::MIN_FRACTION_DIGITS => 0,
//            ],
        ],
        'customFunctions'=>[
            'class'=>'app\components\CustomFunctions',
        ],
        'authManager'=>[
            'class'=>'yii\rbac\DbManager',
            #'connectionID'=>'db',
            'itemTable'=>'authitem', // Tabla que contiene los elementos de autorizacion
            'itemChildTable'=>'authitemchild', // Tabla que contiene los elementos padre-hijo
            'assignmentTable'=>'authassignment', // Tabla que contiene la signacion usuario-autorizacion
        ],
    ],
];
