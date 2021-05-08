<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "akuisisi_data".
 *
 * @property integer $ID
 * @property string $JUDUL
 * @property string $ANAK_JUDUL
 * @property string $PENGARANG
 * @property string $PENGARANG_TAMBAHAN
 * @property string $BADAN_KOPERASI
 * @property string $PENERBIT
 * @property string $TEMPAT_TERBIT
 * @property string $TAHUN_TERBIT
 * @property string $EDISI
 * @property string $NO_KLAS
 * @property string $DESKRIPSI_FISIK
 * @property string $ISBN
 * @property string $CATATAN
 * @property string $SKALA
 * @property string $SUMBER
 * @property string $KETERANGAN_SUMBER
 * @property string $CURRENCY
 * @property double $HARGA
 * @property double $EKSEMPLAR
 * @property string $MEDIA
 * @property string $PENEMPATAN
 * @property string $FREKUENSI
 *
 * @property \common\models\Akuisisi[] $akuisisis
 * @property \common\models\AkuisisiLog[] $akuisisiLogs
 * @property \common\models\AkuisisiRaw[] $akuisisiRaws
 * @property \common\models\AkuisisiRawLog[] $akuisisiRawLogs
 */
class AkuisisiData extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'akuisisi_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['HARGA', 'EKSEMPLAR'], 'number'],
            [['JUDUL', 'ANAK_JUDUL', 'CATATAN'], 'string', 'max' => 1000],
            [['PENGARANG', 'PENGARANG_TAMBAHAN', 'BADAN_KOPERASI', 'PENERBIT', 'TEMPAT_TERBIT', 'DESKRIPSI_FISIK', 'SKALA', 'KETERANGAN_SUMBER'], 'string', 'max' => 500],
            [['TAHUN_TERBIT', 'EDISI', 'NO_KLAS', 'ISBN', 'SUMBER', 'CURRENCY', 'MEDIA', 'PENEMPATAN', 'FREKUENSI'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'JUDUL' => Yii::t('app', 'Judul'),
            'ANAK_JUDUL' => Yii::t('app', 'Anak  Judul'),
            'PENGARANG' => Yii::t('app', 'Pengarang'),
            'PENGARANG_TAMBAHAN' => Yii::t('app', 'Pengarang  Tambahan'),
            'BADAN_KOPERASI' => Yii::t('app', 'Badan  Koperasi'),
            'PENERBIT' => Yii::t('app', 'Penerbit'),
            'TEMPAT_TERBIT' => Yii::t('app', 'Tempat  Terbit'),
            'TAHUN_TERBIT' => Yii::t('app', 'Tahun  Terbit'),
            'EDISI' => Yii::t('app', 'Edisi'),
            'NO_KLAS' => Yii::t('app', 'No  Klas'),
            'DESKRIPSI_FISIK' => Yii::t('app', 'Deskripsi  Fisik'),
            'ISBN' => Yii::t('app', 'Isbn'),
            'CATATAN' => Yii::t('app', 'Catatan'),
            'SKALA' => Yii::t('app', 'Skala'),
            'SUMBER' => Yii::t('app', 'Sumber'),
            'KETERANGAN_SUMBER' => Yii::t('app', 'Keterangan  Sumber'),
            'CURRENCY' => Yii::t('app', 'Currency'),
            'HARGA' => Yii::t('app', 'Harga'),
            'EKSEMPLAR' => Yii::t('app', 'Eksemplar'),
            'MEDIA' => Yii::t('app', 'Media'),
            'PENEMPATAN' => Yii::t('app', 'Penempatan'),
            'FREKUENSI' => Yii::t('app', 'Frekuensi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisis()
    {
        return $this->hasMany(\common\models\Akuisisi::className(), ['Data_ID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisiLogs()
    {
        return $this->hasMany(\common\models\AkuisisiLog::className(), ['Data_ID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisiRaws()
    {
        return $this->hasMany(\common\models\AkuisisiRaw::className(), ['Data_ID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisiRawLogs()
    {
        return $this->hasMany(\common\models\AkuisisiRawLog::className(), ['Data_ID' => 'ID']);
    }


    
}
