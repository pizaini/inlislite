<?php
return 
    [
         
        
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
                'time' => 'hh:mm:ss',
                'datetime' => 'dd-MM-yyyy hh:mm:ss',
            ],
            // format settings for saving each date attribute
            'saveSettings' => [
                'date' => 'yyyy-MM-dd',
                'time' => 'hh:mm:ss',
                'datetime' => 'Y-m-d hh:mm:ss',
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
