<?php

namespace common\models;

use Yii;
use \common\models\base\VLapKriteriaAnggota2 as BaseVLapKriteriaAnggota2;

/**
 * This is the model class for table "v_lap_kriteria_anggota2".
 */
class VLapKriteriaAnggota2 extends BaseVLapKriteriaAnggota2
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_lap_kriteria_anggota2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['kriteria'], 'string', 'max' => 20],
            [['id_dtl_anggota', 'dtl_anggota'], 'string', 'max' => 308]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'kriteria' => 'Kriteria',
            'id_dtl_anggota' => 'Id Dtl Anggota',
            'dtl_anggota' => 'Dtl Anggota',
        ];
    }
}
