<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$modules = array_merge(
       require(__DIR__ . '/modules.php')
);

$language = array_merge(
       require(__DIR__ . '/../../backend/controllers/LanguageController.php')
);

// echo '<pre>'; print_r($language); echo '</pre>';
return [
    'id' => 'app-lkd-inlislite',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'digitalcollection\controllers',
    'language' => $language['lang'],
	'modules' => $modules,
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'user' => [
            'identityClass' => 'common\models\UserMemberOnlines',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_keanggotaanUser', // unique for backend
                'path' => '/keanggotaan' // set it to correct path for backend app.
            ]
        ],
         'session' => [
            'class' => 'yii\web\DbSession',
            'name' => 'keanggotaanSession',
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
/*        'session' => [
            'name' => '_opacSessionId', // unique for backend
            'savePath' => __DIR__ . '/../runtime', // a temporary folder on backend
        ],*/
    ],
    'params' => $params,
];
