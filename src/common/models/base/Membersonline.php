<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "membersonline".
 *
 * @property integer $ID
 * @property string $NoAnggota
 * @property string $NickName
 * @property string $Password
 * @property string $Email
 * @property integer $BranchId
 * @property integer $LocationLoanId
 * @property string $Status
 * @property string $Activation_Code
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property string $auth_key
 *
 * @property \common\models\Branchs $branch
 * @property \common\models\LocationLibrary $locationLoan
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class Membersonline extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'membersonline';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['BranchId', 'LocationLoanId', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['NoAnggota'], 'string', 'max' => 50],
            [['NickName', 'Password', 'Email', 'Status', 'Activation_Code', 'CreateTerminal', 'UpdateTerminal', 'auth_key'], 'string', 'max' => 100],
            [['BranchId'], 'exist', 'skipOnError' => true, 'targetClass' => Branchs::className(), 'targetAttribute' => ['BranchId' => 'ID']],
            [['LocationLoanId'], 'exist', 'skipOnError' => true, 'targetClass' => LocationLibrary::className(), 'targetAttribute' => ['LocationLoanId' => 'ID']],
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
            'NoAnggota' => Yii::t('app', 'No Anggota'),
            'NickName' => Yii::t('app', 'Nick Name'),
            'Password' => Yii::t('app', 'Password'),
            'Email' => Yii::t('app', 'Email'),
            'BranchId' => Yii::t('app', 'Branch ID'),
            'LocationLoanId' => Yii::t('app', 'Location Loan ID'),
            'Status' => Yii::t('app', 'Status'),
            'Activation_Code' => Yii::t('app', 'Activation  Code'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'auth_key' => Yii::t('app', 'Auth Key'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(\common\models\Branchs::className(), ['ID' => 'BranchId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationLoan()
    {
        return $this->hasOne(\common\models\LocationLibrary::className(), ['ID' => 'LocationLoanId']);
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
