<?php
return 
    [   
       
        'gridview' => [
            'class' => 'kartik\grid\Module',
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
