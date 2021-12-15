<?php

namespace common\models;

use Yii;
use \common\models\base\BacaditempatKembali as BaseBacaditempatKembali;

/**
 * This is the model class for table "bacaditempat_kembali".
 */
class BacaditempatKembali extends BaseBacaditempatKembali
{
	
	
      /**
       * @return \yii\db\ActiveQuery
       */
      public function getMemberguess()
      {
          return $this->hasOne(\common\models\Memberguesses::className(), ['NoPengunjung' => 'NoPengunjung']);
      }
	  
	       /**
      * [getFormattedcreatedate description]
      * @return [type] [description]
      */
	public function getFormattedcreatedate()
	{
	    return \DateTime::createFromFormat('Y-m-d H:i:s', $this->CreateDate)->format('d-m-Y H:i:s');
	}

}
