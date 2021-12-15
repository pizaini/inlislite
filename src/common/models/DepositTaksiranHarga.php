<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deposit_taksiran_harga".
 *
 * @property integer $ID
 * @property double $ID_collections
 * @property string $cover
 * @property string $muka_buku
 * @property string $hard_cover
 * @property string $penjilidan
 * @property integer $jumlah_halaman
 * @property string $jenis_kertas_buku
 * @property string $ukuran_buku
 * @property string $kondisi_buku
 * @property string $kondisi_usang
 * @property string $full_color
 *
 * @property Collections $iDCollections
 */
class DepositTaksiranHarga extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deposit_taksiran_harga';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_collections'], 'number'],
            [['jumlah_halaman'], 'integer'],
            [['cover', 'muka_buku', 'hard_cover', 'penjilidan'], 'string', 'max' => 65],
            [['jenis_kertas_buku', 'kondisi_usang'], 'string', 'max' => 25],
            [['ukuran_buku'], 'string', 'max' => 6],
            [['kondisi_buku'], 'string', 'max' => 10],
            [['full_color'], 'string', 'max' => 9],
            [['ID_collections'], 'exist', 'skipOnError' => true, 'targetClass' => Collections::className(), 'targetAttribute' => ['ID_collections' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'ID_collections' => 'Id Collections',
            'cover' => 'Cover',
            'muka_buku' => 'Muka Buku',
            'hard_cover' => 'Hard Cover',
            'penjilidan' => 'Penjilidan',
            'jumlah_halaman' => 'Jumlah Halaman',
            'jenis_kertas_buku' => 'Jenis Kertas Buku',
            'ukuran_buku' => 'Ukuran Buku',
            'kondisi_buku' => 'Kondisi Buku',
            'kondisi_usang' => 'Kondisi Usang',
            'full_color' => 'Full Color',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDCollections()
    {
        return $this->hasOne(Collections::className(), ['ID' => 'ID_collections']);
    }
}
