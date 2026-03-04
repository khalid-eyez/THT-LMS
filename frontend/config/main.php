<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','debug'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => [ 'auth/login' ],
            'authTimeout' => 1800,
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
            'errorAction' => 'admin/auth/error',
            'class' => '\bedezign\yii2\audit\components\web\ErrorHandler'
        ],
        
        // 'urlManager' => [
        //     'enablePrettyUrl' => true,
        //     'showScriptName' => false,
        //     'rules' => [
        //         '<controller:\w+>/<action:\w+>/<id:\w+>' => '<controller>/<action>'
        //     ],
        // ],

        'assetManager' => [
            'bundles' => [

                'yii\bootstrap\BootstrapAsset' => FALSE,

            ],
            'appendTimestamp' => true
        ]
        
    ],
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module'
            
        ],
        'dynagrid'=>[
        'class'=>'\kartik\dynagrid\Module',

        ],
     'gridview' =>  [
            'class' => 'kartik\grid\Module'
        ],
        'shareholder' => [ 'class' => 'frontend\shareholder_module\Shareholder'],
        'loans' => [ 'class' => 'frontend\loans_module\Loan' ],
        'cashbook' => [ 'class' => 'frontend\cashbook_module\Cashbook'],
        'reports' => [ 'class' => 'frontend\reports_module\Report'],
        'admin' => [ 'class' => 'frontend\admin_module\Admin'],
     
      
    ],
    
    'defaultRoute' => 'auth',
    'params' => $params,
];
