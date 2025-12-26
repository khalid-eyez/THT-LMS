<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
          'migration' => [
            'class' => 'bizley\migration\controllers\MigrationController',
            'excludeTables' => [ //because they might exist already 
                'audit_data',
                'audit_entry',
                'audit_error',
                'audit_javascript',
                'audit_mail',
                'audit_trail'
            ],
        ],
          'migrate' => [
      'class' => 'yii\console\controllers\MigrateController',
      'migrationNamespaces' => [
          'bedezign\yii2\audit\migrations',
      ],
          ]
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            // console error handler
            'class' => '\bedezign\yii2\audit\components\console\ErrorHandler',
        ],
    ],
    'params' => $params,
];
