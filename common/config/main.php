<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // uncomment if you want to cache RBAC items hierarchy
            // 'cache' => 'cache',
        ],
        'cdn' => [
            'class' => '\yii2cdn\Cdn',
            'baseUrl' => '/cdn',
            'basePath' => dirname(dirname(__DIR__)) . '/cdn',
            'components' => [
             
                'select2' => [
                    'css' => [
                        [
                            'css/select2.css',
                            '@cdn' => 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.css
                            ', // online version
                        ]
                        ],
                    'js'=>[
                        [
                            'js/select2.min.js',
                            '@cdn'=>'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.js',
                        ]
                    ]
                        ],
                     
                      
            ],
        ],
      
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                //this rule for classwork route
               '<controller:[\w\-]+>/<action:[\w\-]+>/<cid:\w->/classwork' => '<controller>/
                <action>',
                '<controller:[\w\-]+>/<action:[\w\-]+>/<id:\w->' => '<controller>/
                <action>',
                '/access/access-manager'=>'/admin/access/access-manager',
                '/access/add-rule'=>'/admin/access/add-rule',
                '/access/remove-all-roles'=>'/admin/access/remove-all-roles',
                '/access/remove-all-roles-assignments'=>'/admin/access/remove-all-roles-assignments',
                '/access/remove-all-auth-data'=>'/admin/access/remove-all-auth-data',
                '/access/remove-all-permissions'=>'/admin/access/remove-all-permissions',
                '/access/remove-rule'=>'/admin/access/remove-rule',
                '/access/remove-all-rules'=>'/admin/access/remove-all-rules',
                '/access/delete-item'=>'/admin/access/delete-item',
                '/access/remove-children'=>'/admin/access/remove-children',
                '/access/remove-child'=>'/admin/access/remove-child',
                '/access/add-users'=>'/admin/access/add-users',
                '/access/discharge-user'=>'/admin/access/discharge-user',
                '/access/add-children'=>'/admin/access/add-children',
                '/access/item-view'=>'/admin/access/item-view',
                '/access/deassign-all-users'=>'/admin/access/deassign-all-users',
                '/access/add-role'=>'/admin/access/add-role',
                '/access/add-perm'=>'/admin/access/add-perm',
                '/admin/users-list'=>'/admin/admin/users-list',
                '/audit-index/index'=>'/admin/audit-index/index',
                '/audit-index'=>'/admin/audit-index/index',
                '/admin/audit'=>'/admin/admin/audit',
                '/auth/login'=>'/admin/auth/login',
                '/'=>'/admin/auth/login',
                '/auth/logout'=>'/admin/auth/logout',
                '/auth/changepassword'=>'/admin/auth/changepassword',
                '/auth/changepasswordrestrict'=>'/admin/auth/change-password-restrict',
                '/auth/error'=>'/admin/auth/error',
                '/auth/captcha'=>'/admin/auth/captcha',
                '/home/dashboard'=>'/admin/home/dashboard',
                '/storage/monitor'=>'/admin/storage/monitor',
                '/users/create'=>'/admin/users/create',
                '/users/lock'=>'/admin/users/lock',
                '/users/unlock'=>'/admin/users/unlock',
                '/users/delete'=>'/admin/users/delete',
                '/users/update'=>'/admin/users/update',
                '/users/reset-password'=>'/admin/users/reset-password',
                '/loans'=>'/loans/loans/loans',
                '/loans/create-loan'=>'/loans/loans/create-loan',
                '/loans/dashboard'=>'/loans/loans/dashboard',
                '/loans/loan-view'=>'/loans/loans/loan-view'

                 
            ],
        ],
        'hashids' => [
            'class' => 'light\hashids\Hashids',
            'salt' => 'ABDCDGAGAGA',
            'minHashLength' => 5,
            'alphabet' => 'abcdefghigLMNopkRSTuvWzyZ'
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    
    ],
    'modules'=>[
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            // the layout that should be applied for views within this module
            'layout' => '@backend/admin_module/views/layouts/audit.php',
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
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'defaultRoute'=>'/',
        
];
