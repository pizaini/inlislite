<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "locationloan".
 *
 * @property integer $ID
 * @property string $Code
 * @property string $Name
 * @property integer $IsDelete
 * @property string $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property string $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Memberguesses[] $memberguesses
 * @property \common\models\Memberloanauthorizelocation[] $memberloanauthorizelocations
 * @property \common\models\Membersonline[] $membersonlines
 * @property \common\models\Users[] $users
 */
class Locationloan extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'locationloan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'Name'], 'required'],
            [['IsDelete'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Code', 'Name'], 'string', 'max' => 255],
            [['CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100]
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
            'Name' => Yii::t('app', 'Name'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
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
    public function getMemberguesses()
    {
        return $this->hasMany(\common\models\Memberguesses::className(), ['LOCATIONLOANS_ID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberloanauthorizelocations()
    {
        return $this->hasMany(\common\models\Memberloanauthorizelocation::className(), ['LocationLoan_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersonlines()
    {
        return $this->hasMany(\common\models\Membersonline::className(), ['LocationLoanId' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(\common\models\Users::className(), ['LocationLoan_id' => 'ID']);
    }


    
}
