<?php

namespace common\models;

use Yii;
use \common\models\base\Opaclogs as BaseOpaclogs;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the model class for table "Opaclogs".
 */
class Opaclogs extends BaseOpaclogs
{

   public function behaviors()
    {
        return [
        
        ];
    }



}
