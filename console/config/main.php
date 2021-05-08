<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'controllerMap' => [
        'clean-vendors' => [
            'class' => 'common\components\CleanVendorsController',
        ],
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
        // 'db' => [
            // 'class' => 'yii\db\Connection',
            // 'dsn' => 'mysql:host=localhost;dbname=inlislite_v3_fresh;port=3306',
            // 'username' => 'root',
            // 'password' => '',
            // 'charset' => 'utf8',
        // ],
        // 'authManager' => [
            // 'class' => 'yii\rbac\DbManager',
        // ],
		'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
			'enableSession' => false,
            'autoRenewCookie' => true,
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'name' => 'harvestSession',
        ],
    ],
    'params' => $params,
];
