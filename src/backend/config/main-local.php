<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'VYgTdmDqocpFDLk2TJzs72z4xccU98ct',
            // unique CSRF cookie parameter for backend (set by kartik-v/yii2-app-practical)
            'csrfParam' => '_backendInlislite',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = 'yii\debug\Module';
//
//    $config['bootstrap'][] = 'gii';
//    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;