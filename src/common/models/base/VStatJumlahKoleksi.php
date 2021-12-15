<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "v_stat_jumlah_koleksi".
 *
 * @property integer $bulan
 * @property integer $tahun
 * @property integer $jumlah_judul
 * @property integer $jumlah_eksemplar
 * @property integer $jumlah_dijital
 */
class VStatJumlahKoleksi extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_stat_jumlah_koleksi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'tahun', 'jumlah_judul', 'jumlah_eksemplar', 'jumlah_dijital'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bulan' => Yii::t('app', 'Bulan'),
            'tahun' => Yii::t('app', 'Tahun'),
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
