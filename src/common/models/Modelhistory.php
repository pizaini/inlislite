<?php

namespace common\models;

use Yii;
use \common\models\base\Modelhistory as BaseModelhistory;

/**
 * This is the model class for table "modelhistory".
 * @property \common\models\Users $user
 */
class Modelhistory extends BaseModelhistory
{
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'user_id']);
    }
}
