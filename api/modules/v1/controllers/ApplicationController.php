<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;

/**
 * ApplicationController API
 *
 * @author Henry <alvin_vna@yahoo.com>
 */
class ApplicationController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\AppInstalled';    
}

