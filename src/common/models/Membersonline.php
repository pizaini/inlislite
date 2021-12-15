<?php

namespace common\models;

use Yii;
use \common\models\base\Membersonline as BaseMembersonline;

/**
 * This is the model class for table "membersonline".
 */
class Membersonline extends BaseMembersonline
{
    
   public function getMember() 
   { 
       return $this->hasOne(\common\models\Members::className(), ['MemberNo' => 'NoAnggota']); 
   } 
}
