<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "v_lap_kriteria_anggota".
 *
 * @property string $kriteria
 * @property string $id_dtl_anggota
 * @property string $dtl_anggota
 */
class VLapKriteriaAnggota extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_lap_kriteria_anggota';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kriteria'], 'string', 'max' => 14],
            [['id_dtl_anggota', 'dtl_anggota'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kriteria' => Yii::t('app', 'Kriteria'),
            'id_dtl_anggota' => Yii::t('app', 'Id Dtl Anggota'),
            'dtl_anggota' => Yii::t('app', 'Dtl Anggota'),
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
