<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

use \common\models\base\Bacaditempat as BaseBacaditempat;

/**
 * This is the model class for table "bacaditempat".
 */
class Bacaditempat extends BaseBacaditempat
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


    /**
     * @inheritdoc
     * @return type array
     */
    public function behaviors()
    {
        return [
        // \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'CreateDate',
                'updatedAtAttribute' => 'UpdateDate',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'CreateBy',
                'updatedByAttribute' => 'UpdateBy',
            ],
            [
                'class' => TerminalBehavior::className(),
                'createdTerminalAttribute' => 'CreateTerminal',
                'updatedTerminalAttribute' => 'UpdateTerminal',
                'value' => \Yii::$app->request->userIP,
            ],
        ];
    }


}



