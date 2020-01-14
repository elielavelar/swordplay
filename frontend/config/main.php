<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name'=>'Sistema de Administración SwordPlay',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view', 
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>', 
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+\-\w+>/<id:\d+>' => '<controller>/view',  
                ['class' => 'yii\rest\UrlRule', 'controller' => 'site'],
            ],
        ],
        'urlBackendManager' => [
            'class'=>'yii\web\urlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl'=>'/swordplay/backend/web',
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view', 
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>', 
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+\-\w+>/<id:\d+>' => '<controller>/view',  
                ['class' => 'yii\rest\UrlRule', 'controller' => 'site'],
            ],
        ],
        'urlFrontendManager' => [
            'class'=>'yii\web\urlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl'=>'/swordplay/frontend/web',
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view', 
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>', 
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+\-\w+>/<id:\d+>' => '<controller>/view',  
                ['class' => 'yii\rest\UrlRule', 'controller' => 'site'],
            ],
        ],
        'customFunctions'=>[
            'class'=>'frontend\components\CustomFunctions',
        ],
        'view' => [
            'theme' => [
                'class' => \webtoolsnz\AdminLte\Theme::className(),
                'skin' => \webtoolsnz\AdminLte\Theme::SKIN_BLUE_DARK,
            ]
        ],
    ],
    'params' => $params,
];
