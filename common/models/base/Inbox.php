<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "inbox".
 *
 * @property string $UpdatedInDB
 * @property string $ReceivingDateTime
 * @property string $Text
 * @property string $SenderNumber
 * @property string $Coding
 * @property string $UDH
 * @property string $SMSCNumber
 * @property integer $Class
 * @property string $TextDecoded
 * @property integer $ID
 * @property string $RecipientID
 * @property string $Processed
 */
class Inbox extends \yii\db\ActiveRecord
{

    /**
    * ENUM field values
    */
    const CODING_DEFAULT_NO_COMPRESSION = 'Default_No_Compression';
    const CODING_UNICODE_NO_COMPRESSION = 'Unicode_No_Compression';
    const CODING_8BIT = '8bit';
    const CODING_DEFAULT_COMPRESSION = 'Default_Compression';
    const CODING_UNICODE_COMPRESSION = 'Unicode_Compression';
    const PROCESSED_FALSE = 'false';
    const PROCESSED_TRUE = 'true';
    
    var $enum_labels = false;  
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UpdatedInDB', 'ReceivingDateTime'], 'safe'],
            [['Text', 'UDH', 'TextDecoded', 'RecipientID'], 'required'],
            [['Text', 'Coding', 'UDH', 'TextDecoded', 'RecipientID', 'Processed'], 'string'],
            [['Class'], 'integer'],
            [['SenderNumber', 'SMSCNumber'], 'string', 'max' => 20],
            ['Coding', 'in', 'range' => [
                    self::CODING_DEFAULT_NO_COMPRESSION,
                    self::CODING_UNICODE_NO_COMPRESSION,
                    self::CODING_8BIT,
                    self::CODING_DEFAULT_COMPRESSION,
                    self::CODING_UNICODE_COMPRESSION,
                ]
            ],
            ['Processed', 'in', 'range' => [
                    self::PROCESSED_FALSE,
                    self::PROCESSED_TRUE,
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
            'UpdatedInDB' => Yii::t('app', 'Updated In Db'),
            'ReceivingDateTime' => Yii::t('app', 'Receiving Date Time'),
            'Text' => Yii::t('app', 'Text'),
            'SenderNumber' => Yii::t('app', 'Sender Number'),
            'Coding' => Yii::t('app', 'Coding'),
            'UDH' => Yii::t('app', 'Udh'),
            'SMSCNumber' => Yii::t('app', 'Smscnumber'),
            'Class' => Yii::t('app', 'Class'),
            'TextDecoded' => Yii::t('app', 'Text Decoded'),
            'ID' => Yii::t('app', 'ID'),
            'RecipientID' => Yii::t('app', 'Recipient ID'),
            'Processed' => Yii::t('app', 'Processed'),
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
     * get column Coding enum value label 
     * @param string $value
     * @return string
     */
    public static function getCodingValueLabel($value){
        $labels = self::optsCoding();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }
   
    /**
     * column Coding ENUM value labels
     * @return array
     */    
    public static function optsCoding()
    {
        return [
            self::CODING_DEFAULT_NO_COMPRESSION => Yii::t('app', 'Default No Compression'),
            self::CODING_UNICODE_NO_COMPRESSION => Yii::t('app', 'Unicode No Compression'),
            self::CODING_8BIT => Yii::t('app', '8bit'),
            self::CODING_DEFAULT_COMPRESSION => Yii::t('app', 'Default Compression'),
            self::CODING_UNICODE_COMPRESSION => Yii::t('app', 'Unicode Compression'),
        
        ];
    }
    
    /**
     * get column Processed enum value label 
     * @param string $value
     * @return string
     */
    public static function getProcessedValueLabel($value){
        $labels = self::optsProcessed();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }
   
    /**
     * column Processed ENUM value labels
     * @return array
     */    
    public static function optsProcessed()
    {
        return [
            self::PROCESSED_FALSE => Yii::t('app', 'False'),
            self::PROCESSED_TRUE => Yii::t('app', 'True'),
        
        ];
    }
    
}
