<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

$modules = array_merge(
       require(__DIR__ . '/modules.php')
);

$language = array_merge(
       require(__DIR__ . '/../controllers/LanguageController.php')
);

// echo '<pre>'; print_r($language); echo '</pre>';
return [
    'id' => 'app-backend-inlislite',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => $language['lang'],
    'modules' => $modules,
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'autoRenewCookie' => true,
            //'authTimeout' => 14400, //detik 
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'name' => 'backendSession',
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
        'view' => [
            'theme' => 'inliscore\adminlte\Theme',
        ],
        
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        //'class' => '\hscstudio\mimin\components\AccessControl',
        'allowActions' => [
            'site/*',
/*            'site/login',
            'site/error',
            'site/logout',*/
            'debug/*',
            'gii/*',
            //'mimin/*', // only in dev mode
            //'admin/*', // only in dev mode
            //'*'
           

        ]
    ],
    'params' => $params,
];
