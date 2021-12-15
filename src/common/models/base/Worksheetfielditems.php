<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "worksheetfielditems".
 *
 * @property integer $WorksheetField_id
 * @property string $Name
 * @property string $RefferenceMode
 * @property integer $StartPosition
 * @property integer $Length
 * @property string $DefaultValue
 * @property string $IdemTag
 * @property integer $IdemStartPosition
 * @property integer $Refference_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Worksheetfields $worksheetField
 * @property \common\models\Refferences $refference
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class Worksheetfielditems extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'worksheetfielditems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['WorksheetField_id', 'Name', 'RefferenceMode'], 'required'],
            [['WorksheetField_id', 'StartPosition', 'Length', 'IdemStartPosition', 'Refference_id', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Name', 'DefaultValue', 'IdemTag'], 'string', 'max' => 255],
            [['RefferenceMode'], 'string', 'max' => 20],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['WorksheetField_id'], 'exist', 'skipOnError' => true, 'targetClass' => Worksheetfields::className(), 'targetAttribute' => ['WorksheetField_id' => 'ID']],
            [['Refference_id'], 'exist', 'skipOnError' => true, 'targetClass' => Refferences::className(), 'targetAttribute' => ['Refference_id' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'WorksheetField_id' => Yii::t('app', 'Worksheet Field ID'),
            'Name' => Yii::t('app', 'Name'),
            'RefferenceMode' => Yii::t('app', 'Refference Mode'),
            'StartPosition' => Yii::t('app', 'Start Position'),
            'Length' => Yii::t('app', 'Length'),
            'DefaultValue' => Yii::t('app', 'Default Value'),
            'IdemTag' => Yii::t('app', 'Idem Tag'),
            'IdemStartPosition' => Yii::t('app', 'Idem Start Position'),
            'Refference_id' => Yii::t('app', 'Refference ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheetField()
    {
        return $this->hasOne(\common\models\Worksheetfields::className(), ['ID' => 'WorksheetField_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefference()
    {
        return $this->hasOne(\common\models\Refferences::className(), ['ID' => 'Refference_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'CreateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
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


    
}
