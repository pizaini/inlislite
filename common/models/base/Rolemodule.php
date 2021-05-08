<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "rolemodule".
 *
 * @property integer $Role_id
 * @property boolean $IsView
 * @property boolean $IsAdd
 * @property boolean $IsEdit
 * @property boolean $IsDelete
 * @property boolean $IsPrint
 * @property integer $Module_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Modules $module
 * @property \common\models\Roles $role
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class Rolemodule extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rolemodule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Role_id', 'Module_id'], 'required'],
            [['Role_id', 'Module_id', 'CreateBy', 'UpdateBy'], 'integer'],
            [['IsView', 'IsAdd', 'IsEdit', 'IsDelete', 'IsPrint'], 'boolean'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Module_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modules::className(), 'targetAttribute' => ['Module_id' => 'ID']],
            [['Role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['Role_id' => 'ID']],
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
            'Role_id' => Yii::t('app', 'Role ID'),
            'IsView' => Yii::t('app', 'Is View'),
            'IsAdd' => Yii::t('app', 'Is Add'),
            'IsEdit' => Yii::t('app', 'Is Edit'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'IsPrint' => Yii::t('app', 'Is Print'),
            'Module_id' => Yii::t('app', 'Module ID'),
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
    public function getModule()
    {
        return $this->hasOne(\common\models\Modules::className(), ['ID' => 'Module_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(\common\models\Roles::className(), ['ID' => 'Role_id']);
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
