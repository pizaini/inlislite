<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "v_stat_colectionloan".
 *
 * @property integer $tahun
 * @property integer $bulan
 * @property integer $jumlah_judul
 * @property integer $jumlah_eksemplar
 * @property integer $jumlah_dijital
 */
class VStatColectionloan extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_stat_colectionloan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tahun', 'bulan', 'jumlah_judul', 'jumlah_eksemplar', 'jumlah_dijital'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tahun' => Yii::t('app', 'Tahun'),
            'bulan' => Yii::t('app', 'Bulan'),
            'jumlah_judul' => Yii::t('app', 'Jumlah Judul'),
            'jumlah_eksemplar' => Yii::t('app', 'Jumlah Eksemplar'),
            'jumlah_dijital' => Yii::t('app', 'Jumlah Dijital'),
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
