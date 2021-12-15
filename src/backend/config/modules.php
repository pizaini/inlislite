<?php
return 
    [   
       /*'mimin' => [
            'class' => '\hscstudio\mimin\Module',
        ],*/
         
        'admin' => [
            'class' => 'mdm\admin\Module',
            //'layout' => 'left-menu',
            'viewPath'=> '@backend/views/mdm',
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    //'class' => 'setting\umum\controllers\AssignmentController',
                    'userClassName' => 'common\models\User',
                    'idField' => 'id',
                ],
            ],
            
            'menus' => [
                'assignment' => [
                    'label' => 'Grant Access' // change label
                ],

               
                //'route' => null, // disable menu
            ],
        ],
        'akuisisi' => [
            'class' => 'backend\modules\akuisisi\Akuisisi',
        ],
        'deposit' => [
            'class' => 'backend\modules\deposit\deposit',
        ],
        'pengkatalogan' => [
            'class' => 'backend\modules\pengkatalogan\Pengkatalogan',
        ],
        'member' => [
            'class' => 'backend\modules\member\Members',
        ],
        'sirkulasi' => [
            'class' => 'backend\modules\sirkulasi\sirkulasi',
        ],
        'loker' => [
            'class' => 'backend\modules\loker\Loker',
        ],
        'survey' => [

            'class' => 'backend\modules\survey\Survey',
        ],	
	   'bacaditempat' => [

            'class' => 'backend\modules\bacaditempat\Bacaditempat',
        ],
        'opac' => [
            'class' => 'backend\modules\opac\opac',
        ],
        'lkd' => [
            'class' => 'backend\modules\lkd\lkd',
        ],	
        'setting' => [
            'class' => 'backend\modules\setting\Setting',
        ],
        'update' => [
            'class' => 'backend\modules\update\update',
        ],
        'laporan' => [
            'class' => 'backend\modules\laporan\Laporan',
        ],
        'gridview' => [
            'class' => 'kartik\grid\Module',
        ],
        'backuprestore' => [
            'class' => '\oe\modules\backuprestore\Module',
            //'layout' => '@admin-views/layouts/main', or what ever layout you use
        ],
        'datecontrol' => [
            'class' => 'kartik\datecontrol\Module',
            // format settings for displaying each date attribute
            'displaySettings' => [
                'date' => 'dd-MM-yyyy',
                'time' => 'H:i:s',
                'datetime' => 'dd-MM-yyyy H:i:s',
            ],
            // format settings for saving each date attribute
            'saveSettings' => [
                'date' => 'yyyy-MM-dd',
                'time' => 'H:i:s',
                'datetime' => 'Y-m-d H:i:s',
            ],
            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,

            'autoWidgetSettings' => [
                kartik\datecontrol\Module::FORMAT_DATE => ['type'=>2, 'pluginOptions'=>['autoClose'=>true]],
                kartik\datecontrol\Module::FORMAT_DATETIME => [],
                kartik\datecontrol\Module::FORMAT_TIME => [],
            ],
        ],
		
		

		
    ];
