<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "worksheetfields".
 *
 * @property integer $ID
 * @property integer $Field_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property integer $Worksheet_id
 * @property boolean $IsAkuisisi
 * @property boolean $ISDEPOSIT
 * @property boolean $ISDETAILKOLEKSI_PENGOLAHAN
 * @property boolean $ISDETAILKOLEKSI_AKUISISI
 *
 * @property \common\models\Worksheetfielditems[] $worksheetfielditems
 * @property \common\models\Fields $field
 * @property \common\models\Worksheets $worksheet
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class Worksheetfields extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'worksheetfields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Field_id', 'CreateBy', 'UpdateBy', 'Worksheet_id'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['IsAkuisisi', 'ISDEPOSIT', 'ISDETAILKOLEKSI_PENGOLAHAN', 'ISDETAILKOLEKSI_AKUISISI'], 'boolean'],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Field_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fields::className(), 'targetAttribute' => ['Field_id' => 'ID']],
            [['Worksheet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Worksheets::className(), 'targetAttribute' => ['Worksheet_id' => 'ID']],
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
            'ID' => Yii::t('app', 'ID'),
            'Field_id' => Yii::t('app', 'Field ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'Worksheet_id' => Yii::t('app', 'Worksheet ID'),
            'IsAkuisisi' => Yii::t('app', 'Is Akuisisi'),
            'ISDEPOSIT' => Yii::t('app', 'Isdeposit'),
            'ISDETAILKOLEKSI_PENGOLAHAN' => Yii::t('app', 'Isdetailkoleksi  Pengolahan'),
            'ISDETAILKOLEKSI_AKUISISI' => Yii::t('app', 'Isdetailkoleksi  Akuisisi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheetfielditems()
    {
        return $this->hasMany(\common\models\Worksheetfielditems::className(), ['WorksheetField_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(\common\models\Fields::className(), ['ID' => 'Field_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheet()
    {
        return $this->hasOne(\common\models\Worksheets::className(), ['ID' => 'Worksheet_id']);
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
