<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "jenis_perpustakaan".
 *
 * @property integer $ID
 * @property string $Name
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 * @property \common\models\MembersForm[] $membersForms
 * @property \common\models\MembersFormList[] $membersFormLists
 * @property \common\models\MembersInfoForm[] $membersInfoForms
 * @property \common\models\MembersLoanForm[] $membersLoanForms
 * @property \common\models\MembersLoanreturnForm[] $membersLoanreturnForms
 * @property \common\models\MembersOnlineForm[] $membersOnlineForms
 * @property \common\models\MembersOnlineFormEdit[] $membersOnlineFormEdits
 */
class JenisPerpustakaan extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenis_perpustakaan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name'], 'required'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Name'], 'string', 'max' => 50],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
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
    public function getMembersForms()
    {
        return $this->hasMany(\common\models\MembersForm::className(), ['Jenis_Perpustakaan_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersFormLists()
    {
        return $this->hasMany(\common\models\MembersFormList::className(), ['Jenis_Perpustakaan_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersInfoForms()
    {
        return $this->hasMany(\common\models\MembersInfoForm::className(), ['Jenis_Perpustakaan_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersLoanForms()
    {
        return $this->hasMany(\common\models\MembersLoanForm::className(), ['Jenis_Perpustakaan_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersLoanreturnForms()
    {
        return $this->hasMany(\common\models\MembersLoanreturnForm::className(), ['Jenis_Perpustakaan_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersOnlineForms()
    {
        return $this->hasMany(\common\models\MembersOnlineForm::className(), ['Jenis_Perpustakaan_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersOnlineFormEdits()
    {
        return $this->hasMany(\common\models\MembersOnlineFormEdit::className(), ['Jenis_Perpustakaan_id' => 'ID']);
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
