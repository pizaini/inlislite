<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
    //require(__DIR__ . '/params-local.php')
);

$modules = array_merge(
       require(__DIR__ . '/modules.php')
);

$language = array_merge(
       require(__DIR__ . '/../../backend/controllers/LanguageController.php')
);

// echo '<pre>'; print_r($language); echo '</pre>';
return [
    'id' => 'app-pengembalianmandiri',
    'basePath' => dirname(__DIR__),
    'language' => $language['lang'],
    'controllerNamespace' => 'pengembalianmandiri\controllers',
    'bootstrap' => ['log'],
    'modules' => $modules,
    'components' => [
        'request' => [
                // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
                'cookieValidationKey' => 'sBL6-qsadTv4tssQUE1WwDqrX3Bfo',
                // unique CSRF cookie parameter for backend (set by kartik-v/yii2-app-practical)
                'csrfParam' => 'pengembalianmandiri',
            ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
    ],
    'params' => $params,
];
