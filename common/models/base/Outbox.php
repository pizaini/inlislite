<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "outbox".
 *
 * @property string $UpdatedInDB
 * @property string $InsertIntoDB
 * @property string $SendingDateTime
 * @property string $SendBefore
 * @property string $SendAfter
 * @property string $Text
 * @property string $DestinationNumber
 * @property string $Coding
 * @property string $UDH
 * @property integer $Class
 * @property string $TextDecoded
 * @property integer $ID
 * @property string $MultiPart
 * @property integer $RelativeValidity
 * @property string $SenderID
 * @property string $SendingTimeOut
 * @property string $DeliveryReport
 * @property string $CreatorID
 */
class Outbox extends \yii\db\ActiveRecord
{

    /**
    * ENUM field values
    */
    const CODING_DEFAULT_NO_COMPRESSION = 'Default_No_Compression';
    const CODING_UNICODE_NO_COMPRESSION = 'Unicode_No_Compression';
    const CODING_8BIT = '8bit';
    const CODING_DEFAULT_COMPRESSION = 'Default_Compression';
    const CODING_UNICODE_COMPRESSION = 'Unicode_Compression';
    const MULTIPART_FALSE = 'false';
    const MULTIPART_TRUE = 'true';
    const DELIVERYREPORT_DEFAULT = 'default';
    const DELIVERYREPORT_YES = 'yes';
    const DELIVERYREPORT_NO = 'no';
    
    var $enum_labels = false;  
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UpdatedInDB', 'InsertIntoDB', 'SendingDateTime', 'SendBefore', 'SendAfter', 'SendingTimeOut'], 'safe'],
            [['Text', 'Coding', 'UDH', 'TextDecoded', 'MultiPart', 'DeliveryReport', 'CreatorID'], 'string'],
            [['Class', 'RelativeValidity'], 'integer'],
            [['TextDecoded', 'CreatorID'], 'required'],
            [['DestinationNumber'], 'string', 'max' => 20],
            [['SenderID'], 'string', 'max' => 255],
            ['Coding', 'in', 'range' => [
                    self::CODING_DEFAULT_NO_COMPRESSION,
                    self::CODING_UNICODE_NO_COMPRESSION,
                    self::CODING_8BIT,
                    self::CODING_DEFAULT_COMPRESSION,
                    self::CODING_UNICODE_COMPRESSION,
                ]
            ],
            ['MultiPart', 'in', 'range' => [
                    self::MULTIPART_FALSE,
                    self::MULTIPART_TRUE,
                ]
            ],
            ['DeliveryReport', 'in', 'range' => [
                    self::DELIVERYREPORT_DEFAULT,
                    self::DELIVERYREPORT_YES,
                    self::DELIVERYREPORT_NO,
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
            'InsertIntoDB' => Yii::t('app', 'Insert Into Db'),
            'SendingDateTime' => Yii::t('app', 'Sending Date Time'),
            'SendBefore' => Yii::t('app', 'Send Before'),
            'SendAfter' => Yii::t('app', 'Send After'),
            'Text' => Yii::t('app', 'Text'),
            'DestinationNumber' => Yii::t('app', 'Destination Number'),
            'Coding' => Yii::t('app', 'Coding'),
            'UDH' => Yii::t('app', 'Udh'),
            'Class' => Yii::t('app', 'Class'),
            'TextDecoded' => Yii::t('app', 'Text Decoded'),
            'ID' => Yii::t('app', 'ID'),
            'MultiPart' => Yii::t('app', 'Multi Part'),
            'RelativeValidity' => Yii::t('app', 'Relative Validity'),
            'SenderID' => Yii::t('app', 'Sender ID'),
            'SendingTimeOut' => Yii::t('app', 'Sending Time Out'),
            'DeliveryReport' => Yii::t('app', 'Delivery Report'),
            'CreatorID' => Yii::t('app', 'Creator ID'),
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
     * get column MultiPart enum value label 
     * @param string $value
     * @return string
     */
    public static function getMultiPartValueLabel($value){
        $labels = self::optsMultiPart();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }
   
    /**
     * column MultiPart ENUM value labels
     * @return array
     */    
    public static function optsMultiPart()
    {
        return [
            self::MULTIPART_FALSE => Yii::t('app', 'False'),
            self::MULTIPART_TRUE => Yii::t('app', 'True'),
        
        ];
    }
    
    /**
     * get column DeliveryReport enum value label 
     * @param string $value
     * @return string
     */
    public static function getDeliveryReportValueLabel($value){
        $labels = self::optsDeliveryReport();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }
   
    /**
     * column DeliveryReport ENUM value labels
     * @return array
     */    
    public static function optsDeliveryReport()
    {
        return [
            self::DELIVERYREPORT_DEFAULT => Yii::t('app', 'Default'),
            self::DELIVERYREPORT_YES => Yii::t('app', 'Yes'),
            self::DELIVERYREPORT_NO => Yii::t('app', 'No'),
        
        ];
    }
    
}
