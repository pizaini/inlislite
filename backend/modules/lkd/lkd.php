<?php

namespace backend\modules\lkd;

/**
 * index module definition class
 */
class lkd extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\lkd\controllers';

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
                'class' => 'backend\modules\lkd\history\history',
            ],
        ];
    }
}
