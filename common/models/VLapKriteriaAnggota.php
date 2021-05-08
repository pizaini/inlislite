<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "v_lap_kriteria_anggota".
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
            [['id_dtl_anggota', 'dtl_anggota'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kriteria' => 'Kriteria',
            'id_dtl_anggota' => 'Id Dtl Anggota',
            'dtl_anggota' => 'Dtl Anggota',
        ];
    }
}
