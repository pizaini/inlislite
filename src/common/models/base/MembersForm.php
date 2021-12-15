<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "members_form".
 *
 * @property integer $ID
 * @property integer $Member_Field_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property integer $Jenis_Perpustakaan_id
 *
 * @property \common\models\Users $createBy
 * @property \common\models\MemberFields $memberField
 * @property \common\models\JenisPerpustakaan $jenisPerpustakaan
 * @property \common\models\Users $updateBy
 */
class MembersForm extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'members_form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Member_Field_id', 'CreateBy', 'UpdateBy', 'Jenis_Perpustakaan_id'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['Member_Field_id'], 'exist', 'skipOnError' => true, 'targetClass' => MemberFields::className(), 'targetAttribute' => ['Member_Field_id' => 'id']],
            [['Jenis_Perpustakaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisPerpustakaan::className(), 'targetAttribute' => ['Jenis_Perpustakaan_id' => 'ID']],
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
            'Member_Field_id' => Yii::t('app', 'Member  Field ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'Jenis_Perpustakaan_id' => Yii::t('app', 'Jenis  Perpustakaan ID'),
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
    public function getMemberField()
    {
        return $this->hasOne(\common\models\MemberFields::className(), ['id' => 'Member_Field_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPerpustakaan()
    {
        return $this->hasOne(\common\models\JenisPerpustakaan::className(), ['ID' => 'Jenis_Perpustakaan_id']);
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
