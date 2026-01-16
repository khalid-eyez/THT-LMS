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
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            // the layout that should be applied for views within this module
            'layout' => '@frontend/admin_module/views/layouts/audit.php',
            'userIdentifierCallback' => ['common\models\User', 'userIdentifierCallback'],
            'userFilterCallback' => ['common\models\User', 'filterByUserIdentifierCallback'],
            // Name of the component to use for database access
            'db' => 'db', 
            // List of actions to track. '*' is allowed as the last character to use as wildcard
            'trackActions' => ['*'], 
            // Actions to ignore. '*' is allowed as the last character to use as wildcard (eg 'debug/*')
            'ignoreActions' => ['audit/*', 'debug/*'],
            // Maximum age (in days) of the audit entries before they are truncated
            'maxAge' => 'debug',
            // Role or list of roles with access to the viewer, null for everyone (if the user matches)
            'accessRoles' => ['view_audit_data'],
            // Compress extra data generated or just keep in text? For people who don't like binary data in the DB
            'compressData' => true,
            // If the value is a simple string, it is the identifier of an internal to activate (with default settings)
            // If the entry is a '<key>' => '<string>|<array>' it is a new panel. It can optionally override a core panel or add a new one.
            // 'panels' => [
            //     'audit/log',
            //     'audit/error',
            //     'audit/trail',
            //     // 'app/views' => [
            //     //     'class' => 'app\panels\ViewsPanel',
            //     //     // ...
            //     // ],
            // ],
        ],
    ],
    
    'defaultRoute' => 'auth',
    'params' => $params,
];
