<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "outbox_multipart".
 *
 * @property string $Text
 * @property string $Coding
 * @property string $UDH
 * @property integer $Class
 * @property string $TextDecoded
 * @property integer $ID
 * @property integer $SequencePosition
 */
class OutboxMultipart extends \yii\db\ActiveRecord
{

    /**
    * ENUM field values
    */
    const CODING_DEFAULT_NO_COMPRESSION = 'Default_No_Compression';
    const CODING_UNICODE_NO_COMPRESSION = 'Unicode_No_Compression';
    const CODING_8BIT = '8bit';
    const CODING_DEFAULT_COMPRESSION = 'Default_Compression';
    const CODING_UNICODE_COMPRESSION = 'Unicode_Compression';
    
    var $enum_labels = false;  
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outbox_multipart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Text', 'Coding', 'UDH', 'TextDecoded'], 'string'],
            [['Class', 'ID', 'SequencePosition'], 'integer'],
            [['ID', 'SequencePosition'], 'required'],
            ['Coding', 'in', 'range' => [
                    self::CODING_DEFAULT_NO_COMPRESSION,
                    self::CODING_UNICODE_NO_COMPRESSION,
                    self::CODING_8BIT,
                    self::CODING_DEFAULT_COMPRESSION,
                    self::CODING_UNICODE_COMPRESSION,
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
            'Text' => Yii::t('app', 'Text'),
            'Coding' => Yii::t('app', 'Coding'),
            'UDH' => Yii::t('app', 'Udh'),
            'Class' => Yii::t('app', 'Class'),
            'TextDecoded' => Yii::t('app', 'Text Decoded'),
            'ID' => Yii::t('app', 'ID'),
            'SequencePosition' => Yii::t('app', 'Sequence Position'),
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
    
}
