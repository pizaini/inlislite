<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "v_stat_jenis_pendidikan".
 *
 * @property string $Keterangan
 * @property integer $Jumlah
 * @property string $Tahun
 */
class VStatJenisPendidikan extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_stat_jenis_pendidikan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Keterangan'], 'required'],
            [['Jumlah'], 'integer'],
            [['Keterangan'], 'string', 'max' => 50],
            [['Tahun'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Keterangan' => Yii::t('app', 'Keterangan'),
            'Jumlah' => Yii::t('app', 'Jumlah'),
            'Tahun' => Yii::t('app', 'Tahun'),
        ];
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
