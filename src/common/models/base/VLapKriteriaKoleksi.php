<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "v_lap_kriteria_koleksi".
 *
 * @property string $kriteria
 * @property string $id_dtl_kriteria
 * @property string $dtl_kriteria
 */
class VLapKriteriaKoleksi extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_lap_kriteria_koleksi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_dtl_kriteria', 'dtl_kriteria'], 'string'],
            [['kriteria'], 'string', 'max' => 19]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kriteria' => Yii::t('app', 'Kriteria'),
            'id_dtl_kriteria' => Yii::t('app', 'Id Dtl Kriteria'),
            'dtl_kriteria' => Yii::t('app', 'Dtl Kriteria'),
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
