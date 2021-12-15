<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;
/**
 * This is the model class for table "deposit_kelompok_penerbit".
 *
 * @property integer $ID
 * @property string $Name
 * @property integer $CreateBy
 * @property string $CreateDate
 *
 * @property Users $createBy
 */
class DepositKelompokPenerbit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deposit_kelompok_penerbit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CreateBy'], 'integer'],
            [['CreateDate'], 'safe'],
            [['Name'], 'string', 'max' => 111],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Name' => 'Name',
            'CreateBy' => 'Create By',
            'CreateDate' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(Users::className(), ['ID' => 'CreateBy']);
    }


    /** 
     * @inheritdoc 
     * @return type array 
     */  
    public function behaviors() 
    { 
        return [ 
             \nhkey\arh\ActiveRecordHistoryBehavior::className(), 
            [ 
                'class' => TimestampBehavior::className(), 
                'createdAtAttribute' => 'CreateDate', 
                'value' => new \yii\db\Expression('NOW()'), 
            ], 
            [ 
                'class' => BlameableBehavior::className(), 
                'createdByAttribute' => 'CreateBy', 
            ], 

        ]; 
    } 
}
