<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "registrasi".
 *
 * @property integer $ID
 * @property string $NamaPerpustakaan
 * @property string $ActivationCode
 * @property string $JenisPerpustakaan
 * @property string $CreateDate
 * @property string $CreateTerminal
 */
class Registrasi extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registrasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ActivationCode', 'NamaPerpustakaan', 'JenisPerpustakaan', 'Negara', 'CreateDate', 'CreateTerminal'], 'required'],
            [['CreateDate'], 'safe'],
            [['NamaPerpustakaan', 'ActivationCode', 'NamaPerpustakaan', 'JenisPerpustakaan', 'Negara', 'Provinsi', 'CreateTerminal'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'NamaPerpustakaan' => Yii::t('app', 'Nama Perpustakaan'),
            'JenisPerpustakaan' => Yii::t('app', 'Jenis Perpustakaan'),
            'ActivationCode' => Yii::t('app', 'Activation Code'),
            'Negara' => Yii::t('app', 'Negara'),
            'Provinsi' => Yii::t('app', 'Provinsi'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
        ];
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
             /*\nhkey\arh\ActiveRecordHistoryBehavior::className(),
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
            ],*/
        ];
    }


    
}
