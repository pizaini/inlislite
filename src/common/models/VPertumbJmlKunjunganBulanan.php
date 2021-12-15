<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "v_pertumb_jml_kunjungan_bulanan".
 *
 * @property string $kriteria
 * @property integer $tahun
 * @property integer $bulan
 * @property integer $jumlah
 */
class VPertumbJmlKunjunganBulanan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_pertumb_jml_kunjungan_bulanan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tahun', 'bulan', 'jumlah'], 'integer'],
            [['kriteria'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kriteria' => 'Kriteria',
            'tahun' => 'Tahun',
            'bulan' => 'Bulan',
            'jumlah' => 'Jumlah',
        ];
    }
}
