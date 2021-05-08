<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "phones".
 *
 * @property string $ID
 * @property string $UpdatedInDB
 * @property string $InsertIntoDB
 * @property string $TimeOut
 * @property string $Send
 * @property string $Receive
 * @property string $IMEI
 * @property string $Client
 * @property integer $Battery
 * @property integer $Signal
 * @property integer $Sent
 * @property integer $Received
 */
class Phones extends \yii\db\ActiveRecord
{

    /**
    * ENUM field values
    */
    const SEND_YES = 'yes';
    const SEND_NO = 'no';
    const RECEIVE_YES = 'yes';
    const RECEIVE_NO = 'no';
    
    var $enum_labels = false;  
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IMEI', 'Client'], 'required'],
            [['ID', 'Send', 'Receive', 'Client'], 'string'],
            [['UpdatedInDB', 'InsertIntoDB', 'TimeOut'], 'safe'],
            [['Battery', 'Signal', 'Sent', 'Received'], 'integer'],
            [['IMEI'], 'string', 'max' => 35],
            ['Send', 'in', 'range' => [
                    self::SEND_YES,
                    self::SEND_NO,
                ]
            ],
            ['Receive', 'in', 'range' => [
                    self::RECEIVE_YES,
                    self::RECEIVE_NO,
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'UpdatedInDB' => Yii::t('app', 'Updated In Db'),
            'InsertIntoDB' => Yii::t('app', 'Insert Into Db'),
            'TimeOut' => Yii::t('app', 'Time Out'),
            'Send' => Yii::t('app', 'Send'),
            'Receive' => Yii::t('app', 'Receive'),
            'IMEI' => Yii::t('app', 'Imei'),
            'Client' => Yii::t('app', 'Client'),
            'Battery' => Yii::t('app', 'Battery'),
            'Signal' => Yii::t('app', 'Signal'),
            'Sent' => Yii::t('app', 'Sent'),
            'Received' => Yii::t('app', 'Received'),
        ];
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
        \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
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


    
    /**
     * get column Send enum value label 
     * @param string $value
     * @return string
     */
    public static function getSendValueLabel($value){
        $labels = self::optsSend();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }
   
    /**
     * column Send ENUM value labels
     * @return array
     */    
    public static function optsSend()
    {
        return [
            self::SEND_YES => Yii::t('app', 'Yes'),
            self::SEND_NO => Yii::t('app', 'No'),
        
        ];
    }
    
    /**
     * get column Receive enum value label 
     * @param string $value
     * @return string
     */
    public static function getReceiveValueLabel($value){
        $labels = self::optsReceive();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }
   
    /**
     * column Receive ENUM value labels
     * @return array
     */    
    public static function optsReceive()
    {
        return [
            self::RECEIVE_YES => Yii::t('app', 'Yes'),
            self::RECEIVE_NO => Yii::t('app', 'No'),
        
        ];
    }
    
}
