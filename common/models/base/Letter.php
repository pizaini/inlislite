<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;
use common\models\DepositWs;

/**
 * This is the base-model class for table "letter".
 *
 * @property integer $ID
 * @property string $TYPE_OF_DELIVERY
 * @property string $LETTER_DATE
 * @property string $LETTER_NUMBER
 * @property string $ACCEPT_DATE
 * @property string $SENDER
 * @property integer $PHONE
 * @property string $INTENDED_TO
 * @property integer $IS_PRINTED
 * @property string $CreateDate
 * @property integer $CreateBy
 * @property string $CreateTerminal
 * @property string $UpdateDate
 * @property integer $UpdateBy
 * @property string $UpdateTerminal
 * @property integer $PUBLISHER_ID
 * @property string $LETTER_NUMBER_UT
 * @property integer $IS_SENDEDEMAIL
 * @property integer $IS_NOTE
 * @property string $LANG
 *
 * @property \common\models\DepositWs $pUBLISHER
 * @property \common\models\LetterDetail[] $letterDetails
 */
class Letter extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'letter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['LETTER_DATE', 'ACCEPT_DATE', 'CreateDate', 'UpdateDate'], 'safe'],
            [['PHONE', 'IS_PRINTED', 'CreateBy', 'UpdateBy', 'PUBLISHER_ID', 'IS_SENDEDEMAIL', 'IS_NOTE'], 'integer'],
            [['TYPE_OF_DELIVERY'], 'string', 'max' => 21],
            [['LETTER_NUMBER'], 'string', 'max' => 35],
            [['SENDER', 'INTENDED_TO'], 'string', 'max' => 155],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 111],
            [['LETTER_NUMBER_UT'], 'string', 'max' => 45],
            [['LANG'], 'string', 'max' => 20],
            [['PUBLISHER_ID'], 'exist', 'skipOnError' => true, 'targetClass' => DepositWs::className(), 'targetAttribute' => ['PUBLISHER_ID' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'TYPE_OF_DELIVERY' => 'Type  Of  Delivery',
            'LETTER_DATE' => 'Letter  Date',
            'LETTER_NUMBER' => 'Letter  Number',
            'ACCEPT_DATE' => 'Accept  Date',
            'SENDER' => 'Sender',
            'PHONE' => 'Phone',
            'INTENDED_TO' => 'Intended  To',
            'IS_PRINTED' => 'Is  Printed',
            'CreateDate' => 'Create Date',
            'CreateBy' => 'Create By',
            'CreateTerminal' => 'Create Terminal',
            'UpdateDate' => 'Update Date',
            'UpdateBy' => 'Update By',
            'UpdateTerminal' => 'Update Terminal',
            'PUBLISHER_ID' => 'Publisher  ID',
            'LETTER_NUMBER_UT' => 'Letter  Number  Ut',
            'IS_SENDEDEMAIL' => 'Is  Sendedemail',
            'IS_NOTE' => 'Is  Note',
            'LANG' => 'Lang',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepositWs()
    {
        return $this->hasOne(\common\models\DepositWs::className(), ['ID' => 'PUBLISHER_ID']);
    }

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getUsers() 
    { 
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'CreateBy']);
    } 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetterDetails()
    {
        return $this->hasMany(\common\models\LetterDetail::className(), ['LETTER_ID' => 'ID']);
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
             \nhkey\arh\ActiveRecordHistoryBehavior::className(),
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
