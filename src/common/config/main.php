<?php

return [
    'timeZone' => 'Asia/Jakarta',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'config' => [
            'class'         => 'common\components\Config', // Class (Required)
            'db'            => 'db',                                 // Database Connection ID (Optional)
            'tableName'     => '{{%settingparameters}}',                        // Table Name (Optioanl)
            'cacheId'       => 'cache',                              // Cache Id. Defaults to NULL (Optional)
            'cacheKey'      => 'config.cache',                       // Key identifying the cache value (Required only if cacheId is set)
            'cacheDuration' => 100,
                                             // Cache Expiration time in seconds. 0 means never expire. Defaults to 0 (Optional)
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
                // configure more hosts if you have a cluster
            ],
        ],
        'sirkulasi' =>[ // nama, yang nantinya digunakan untuk memanggil component
            'class'=>'common\components\SirkulasiComponent' // sesuaikan dengan nama class yang telah dibuat
        ],
        'location' =>[ // nama, yang nantinya digunakan untuk memanggil component
            'class'=>'common\components\LocationComponent' // sesuaikan dengan nama class yang telah dibuat
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        /*'session' => [
            'class' => 'yii\web\DbSession',

            // Set the following if you want to use DB component other than
            // default 'db'.
            // 'db' => 'mydb',

            // To override default session table, set the following
            // 'sessionTable' => 'my_session',
        ],*/
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
		//get user ip
       'ReadHttpHeader' => [
                  'class' => 'common\components\ReadHttpHeader'
        ],
        // set target language to be indonesia
        'i18n' => [
            'translations' => [
                'backend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    'fileMap' => [
                        'backend' => ['app.php'],
                       
                    ]
                ],
                'rbac-admin'=>[
                            'class' => 'yii\i18n\PhpMessageSource',
                            //'sourceLanguage' => 'id',
                            'basePath' => '@backend/messages',
                            /*'fileMap' => [
                                'admin' => ['rbac-admin.php'],

                            ]*/
               ]
            ],
        ],
        'backuprestore' => [
            'class' => '\oe\modules\backuprestore\Module',
        ],
    ],

];
