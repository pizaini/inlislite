<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "fields".
 *
 * @property integer $ID
 * @property string $Tag
 * @property string $Name
 * @property boolean $Fixed
 * @property boolean $Enabled
 * @property integer $Length
 * @property boolean $Repeatable
 * @property boolean $Mandatory
 * @property boolean $IsCustomable
 * @property integer $Format_id
 * @property integer $Group_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property string $DEFAULTSUBTAG
 * @property boolean $ISSUBSERIAL
 *
 * @property \common\models\Fielddatas[] $fielddatas
 * @property \common\models\Fieldindicator1s[] $fieldindicator1s
 * @property \common\models\Fieldindicator2s[] $fieldindicator2s
 * @property \common\models\Users $createBy
 * @property \common\models\Fieldgroups $group
 * @property \common\models\Formats $format
 * @property \common\models\Users $updateBy
 * @property \common\models\Settingcatalogdetail[] $settingcatalogdetails
 * @property \common\models\Worksheetfields[] $worksheetfields
 */
class Fields extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Tag', 'Name', 'Format_id', 'Group_id'], 'required'],
            [['Fixed', 'Enabled', 'Repeatable', 'Mandatory', 'IsCustomable', 'ISSUBSERIAL'], 'boolean'],
            [['Length', 'Format_id', 'Group_id', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Tag'], 'string', 'max' => 3],
            [['Name', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['DEFAULTSUBTAG'], 'string', 'max' => 12],
            [['Tag', 'Format_id'], 'unique', 'targetAttribute' => ['Tag', 'Format_id'], 'message' => 'The combination of Tag and Format ID has already been taken.'],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['Group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fieldgroups::className(), 'targetAttribute' => ['Group_id' => 'ID']],
            [['Format_id'], 'exist', 'skipOnError' => true, 'targetClass' => Formats::className(), 'targetAttribute' => ['Format_id' => 'ID']],
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
            'Tag' => Yii::t('app', 'Tag'),
            'Name' => Yii::t('app', 'Name'),
            'Fixed' => Yii::t('app', 'Fixed'),
            'Enabled' => Yii::t('app', 'Enabled'),
            'Length' => Yii::t('app', 'Length'),
            'Repeatable' => Yii::t('app', 'Repeatable'),
            'Mandatory' => Yii::t('app', 'Mandatory'),
            'IsCustomable' => Yii::t('app', 'Is Customable'),
            'Format_id' => Yii::t('app', 'Format ID'),
            'Group_id' => Yii::t('app', 'Group ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'DEFAULTSUBTAG' => Yii::t('app', 'Defaultsubtag'),
            'ISSUBSERIAL' => Yii::t('app', 'Issubserial'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFielddatas()
    {
        return $this->hasMany(\common\models\Fielddatas::className(), ['Field_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldindicator1s()
    {
        return $this->hasMany(\common\models\Fieldindicator1s::className(), ['Field_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldindicator2s()
    {
        return $this->hasMany(\common\models\Fieldindicator2s::className(), ['Field_id' => 'ID']);
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
    public function getGroup()
    {
        return $this->hasOne(\common\models\Fieldgroups::className(), ['ID' => 'Group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormat()
    {
        return $this->hasOne(\common\models\Formats::className(), ['ID' => 'Format_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingcatalogdetails()
    {
        return $this->hasMany(\common\models\Settingcatalogdetail::className(), ['Field_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheetfields()
    {
        return $this->hasMany(\common\models\Worksheetfields::className(), ['Field_id' => 'ID']);
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
