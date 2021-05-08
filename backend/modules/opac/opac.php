<?php

namespace backend\modules\opac;

/**
 * index module definition class
 */
class opac extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\opac\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->modules = [
            'history' => [
                // you should consider using a shorter namespace here!
                'class' => 'backend\modules\opac\history\history',
            ],

        ];
    }
}
