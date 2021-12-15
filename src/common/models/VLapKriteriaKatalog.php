<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "v_lap_kriteria_katalog".
 *
 * @property string $kriteria
 * @property string $id_dtl_kriteria
 * @property string $dtl_kriteria
 */
class VLapKriteriaKatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_lap_kriteria_katalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_dtl_kriteria', 'dtl_kriteria'], 'string'],
            [['kriteria'], 'string', 'max' => 13],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kriteria' => 'Kriteria',
            'id_dtl_kriteria' => 'Id Dtl Kriteria',
            'dtl_kriteria' => 'Dtl Kriteria',
        ];
    }
}
