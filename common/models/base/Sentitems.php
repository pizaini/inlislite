<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "sentitems".
 *
 * @property string $UpdatedInDB
 * @property string $InsertIntoDB
 * @property string $SendingDateTime
 * @property string $DeliveryDateTime
 * @property string $Text
 * @property string $DestinationNumber
 * @property string $Coding
 * @property string $UDH
 * @property string $SMSCNumber
 * @property integer $Class
 * @property string $TextDecoded
 * @property integer $ID
 * @property string $SenderID
 * @property integer $SequencePosition
 * @property string $Status
 * @property integer $StatusError
 * @property integer $TPMR
 * @property integer $RelativeValidity
 * @property string $CreatorID
 * @property double $CollectionLoanItem_id
 *
 * @property \common\models\Collectionloanitems $collectionLoanItem
 */
class Sentitems extends \yii\db\ActiveRecord
{

    /**
    * ENUM field values
    */
    const CODING_DEFAULT_NO_COMPRESSION = 'Default_No_Compression';
    const CODING_UNICODE_NO_COMPRESSION = 'Unicode_No_Compression';
    const CODING_8BIT = '8bit';
    const CODING_DEFAULT_COMPRESSION = 'Default_Compression';
    const CODING_UNICODE_COMPRESSION = 'Unicode_Compression';
    const STATUS_SENDINGOK = 'SendingOK';
    const STATUS_SENDINGOKNOREPORT = 'SendingOKNoReport';
    const STATUS_SENDINGERROR = 'SendingError';
    const STATUS_DELIVERYOK = 'DeliveryOK';
    const STATUS_DELIVERYFAILED = 'DeliveryFailed';
    const STATUS_DELIVERYPENDING = 'DeliveryPending';
    const STATUS_DELIVERYUNKNOWN = 'DeliveryUnknown';
    const STATUS_ERROR = 'Error';
    
    var $enum_labels = false;  
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sentitems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UpdatedInDB', 'InsertIntoDB', 'SendingDateTime', 'DeliveryDateTime'], 'safe'],
            [['Text', 'UDH', 'TextDecoded', 'ID', 'SenderID', 'SequencePosition', 'CreatorID'], 'required'],
            [['Text', 'Coding', 'UDH', 'TextDecoded', 'Status', 'CreatorID'], 'string'],
            [['Class', 'ID', 'SequencePosition', 'StatusError', 'TPMR', 'RelativeValidity'], 'integer'],
            [['CollectionLoanItem_id'], 'number'],
            [['DestinationNumber', 'SMSCNumber'], 'string', 'max' => 20],
            [['SenderID'], 'string', 'max' => 255],
            [['CollectionLoanItem_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionloanitems::className(), 'targetAttribute' => ['CollectionLoanItem_id' => 'ID']],
            ['Coding', 'in', 'range' => [
                    self::CODING_DEFAULT_NO_COMPRESSION,
                    self::CODING_UNICODE_NO_COMPRESSION,
                    self::CODING_8BIT,
                    self::CODING_DEFAULT_COMPRESSION,
                    self::CODING_UNICODE_COMPRESSION,
                ]
            ],
            ['Status', 'in', 'range' => [
                    self::STATUS_SENDINGOK,
                    self::STATUS_SENDINGOKNOREPORT,
                    self::STATUS_SENDINGERROR,
                    self::STATUS_DELIVERYOK,
                    self::STATUS_DELIVERYFAILED,
                    self::STATUS_DELIVERYPENDING,
                    self::STATUS_DELIVERYUNKNOWN,
                    self::STATUS_ERROR,
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
            'DeliveryDateTime' => Yii::t('app', 'Delivery Date Time'),
            'Text' => Yii::t('app', 'Text'),
            'DestinationNumber' => Yii::t('app', 'Destination Number'),
            'Coding' => Yii::t('app', 'Coding'),
            'UDH' => Yii::t('app', 'Udh'),
            'SMSCNumber' => Yii::t('app', 'Smscnumber'),
            'Class' => Yii::t('app', 'Class'),
            'TextDecoded' => Yii::t('app', 'Text Decoded'),
            'ID' => Yii::t('app', 'ID'),
            'SenderID' => Yii::t('app', 'Sender ID'),
            'SequencePosition' => Yii::t('app', 'Sequence Position'),
            'Status' => Yii::t('app', 'Status'),
            'StatusError' => Yii::t('app', 'Status Error'),
            'TPMR' => Yii::t('app', 'Tpmr'),
            'RelativeValidity' => Yii::t('app', 'Relative Validity'),
            'CreatorID' => Yii::t('app', 'Creator ID'),
            'CollectionLoanItem_id' => Yii::t('app', 'Collection Loan Item ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionLoanItem()
    {
        return $this->hasOne(\common\models\Collectionloanitems::className(), ['ID' => 'CollectionLoanItem_id']);
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
     * get column Status enum value label 
     * @param string $value
     * @return string
     */
    public static function getStatusValueLabel($value){
        $labels = self::optsStatus();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }
   
    /**
     * column Status ENUM value labels
     * @return array
     */    
    public static function optsStatus()
    {
        return [
            self::STATUS_SENDINGOK => Yii::t('app', 'Sending Ok'),
            self::STATUS_SENDINGOKNOREPORT => Yii::t('app', 'Sending Okno Report'),
            self::STATUS_SENDINGERROR => Yii::t('app', 'Sending Error'),
            self::STATUS_DELIVERYOK => Yii::t('app', 'Delivery Ok'),
            self::STATUS_DELIVERYFAILED => Yii::t('app', 'Delivery Failed'),
            self::STATUS_DELIVERYPENDING => Yii::t('app', 'Delivery Pending'),
            self::STATUS_DELIVERYUNKNOWN => Yii::t('app', 'Delivery Unknown'),
            self::STATUS_ERROR => Yii::t('app', 'Error'),
        
        ];
    }
    
}
