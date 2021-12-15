<?php

namespace backend\modules\member;

class Members extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\member\controllers';
    public $defaultRoute = 'member';
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
