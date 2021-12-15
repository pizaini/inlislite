<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "modules".
 *
 * @property integer $ID
 * @property string $Name
 * @property string $URL
 * @property integer $SortNo
 * @property boolean $IsPublish
 * @property string $ClassName
 * @property boolean $IsHeader
 * @property integer $ModuleLevel
 * @property integer $Application_id
 * @property integer $ParentID
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Applications $application
 * @property \common\models\Modules $parent
 * @property \common\models\Modules[] $modules
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 * @property \common\models\Rolemodule[] $rolemodules
 * @property \common\models\Roles[] $roles
 */
class Modules extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Application_id'], 'required'],
            [['SortNo', 'ModuleLevel', 'Application_id', 'ParentID', 'CreateBy', 'UpdateBy'], 'integer'],
            [['IsPublish', 'IsHeader'], 'boolean'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Name', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['URL'], 'string', 'max' => 255],
            [['ClassName'], 'string', 'max' => 50],
            [['Application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Applications::className(), 'targetAttribute' => ['Application_id' => 'ID']],
            [['ParentID'], 'exist', 'skipOnError' => true, 'targetClass' => Modules::className(), 'targetAttribute' => ['ParentID' => 'ID']],
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
            'Name' => Yii::t('app', 'Name'),
            'URL' => Yii::t('app', 'Url'),
            'SortNo' => Yii::t('app', 'Sort No'),
            'IsPublish' => Yii::t('app', 'Is Publish'),
            'ClassName' => Yii::t('app', 'Class Name'),
            'IsHeader' => Yii::t('app', 'Is Header'),
            'ModuleLevel' => Yii::t('app', 'Module Level'),
            'Application_id' => Yii::t('app', 'Application ID'),
            'ParentID' => Yii::t('app', 'Parent ID'),
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
    public function getApplication()
    {
        return $this->hasOne(\common\models\Applications::className(), ['ID' => 'Application_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(\common\models\Modules::className(), ['ID' => 'ParentID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(\common\models\Modules::className(), ['ParentID' => 'ID']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getRolemodules()
    {
        return $this->hasMany(\common\models\Rolemodule::className(), ['Module_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(\common\models\Roles::className(), ['ID' => 'Role_id'])->viaTable('rolemodule', ['Module_id' => 'ID']);
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
