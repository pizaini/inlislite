<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "tujuan_kunjungan".
 *
 * @property integer $ID
 * @property string $Code
 * @property string $TujuanKunjungan
 * @property integer $Member
 * @property integer $NonMember
 * @property integer $Rombongan
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Groupguesses[] $groupguesses
 * @property \common\models\Memberguesses[] $memberguesses
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class TujuanKunjungan extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tujuan_kunjungan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'TujuanKunjungan'], 'required'],
            [['Member', 'NonMember', 'Rombongan', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Code', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['TujuanKunjungan'], 'string', 'max' => 255],
            [['Code'], 'unique'],
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
            'Code' => Yii::t('app', 'Code'),
            'TujuanKunjungan' => Yii::t('app', 'Tujuan Kunjungan'),
            'Member' => Yii::t('app', 'Member'),
            'NonMember' => Yii::t('app', 'Non Member'),
            'Rombongan' => Yii::t('app', 'Rombongan'),
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
    public function getGroupguesses()
    {
        return $this->hasMany(\common\models\Groupguesses::className(), ['TujuanKunjungan_ID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberguesses()
    {
        return $this->hasMany(\common\models\Memberguesses::className(), ['TujuanKunjungan_Id' => 'ID']);
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
