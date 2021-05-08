<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "akuisisi_map".
 *
 * @property integer $WorksheetID
 * @property string $Judul
 * @property string $AnakJudul
 * @property string $Pengarang
 * @property string $PengarangTambahan
 * @property string $BadanKoperasi
 * @property string $Penerbit
 * @property string $TempatTerbit
 * @property string $TahunTerbit
 * @property string $Edisi
 * @property string $NoKlas
 * @property string $DeskripsiFisik
 * @property string $ISBN
 * @property string $Catatan
 * @property string $Skala
 * @property string $Sumber
 * @property string $KeteranganSumber
 * @property integer $SortID
 * @property string $Media
 * @property string $Penempatan
 * @property string $Frekuensi
 *
 * @property \common\models\AkuisisiWorksheet $worksheet
 */
class AkuisisiMap extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'akuisisi_map';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['WorksheetID'], 'required'],
            [['WorksheetID', 'SortID'], 'integer'],
            [['Judul', 'AnakJudul', 'Pengarang', 'PengarangTambahan', 'BadanKoperasi', 'Penerbit', 'TempatTerbit', 'TahunTerbit', 'Edisi', 'NoKlas', 'DeskripsiFisik', 'ISBN', 'Catatan', 'Skala', 'Sumber', 'KeteranganSumber'], 'string', 'max' => 5],
            [['Media', 'Penempatan', 'Frekuensi'], 'string', 'max' => 50],
            [['WorksheetID'], 'exist', 'skipOnError' => true, 'targetClass' => AkuisisiWorksheet::className(), 'targetAttribute' => ['WorksheetID' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'WorksheetID' => Yii::t('app', 'Worksheet ID'),
            'Judul' => Yii::t('app', 'Judul'),
            'AnakJudul' => Yii::t('app', 'Anak Judul'),
            'Pengarang' => Yii::t('app', 'Pengarang'),
            'PengarangTambahan' => Yii::t('app', 'Pengarang Tambahan'),
            'BadanKoperasi' => Yii::t('app', 'Badan Koperasi'),
            'Penerbit' => Yii::t('app', 'Penerbit'),
            'TempatTerbit' => Yii::t('app', 'Tempat Terbit'),
            'TahunTerbit' => Yii::t('app', 'Tahun Terbit'),
            'Edisi' => Yii::t('app', 'Edisi'),
            'NoKlas' => Yii::t('app', 'No Klas'),
            'DeskripsiFisik' => Yii::t('app', 'Deskripsi Fisik'),
            'ISBN' => Yii::t('app', 'Isbn'),
            'Catatan' => Yii::t('app', 'Catatan'),
            'Skala' => Yii::t('app', 'Skala'),
            'Sumber' => Yii::t('app', 'Sumber'),
            'KeteranganSumber' => Yii::t('app', 'Keterangan Sumber'),
            'SortID' => Yii::t('app', 'Sort ID'),
            'Media' => Yii::t('app', 'Media'),
            'Penempatan' => Yii::t('app', 'Penempatan'),
            'Frekuensi' => Yii::t('app', 'Frekuensi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheet()
    {
        return $this->hasOne(\common\models\AkuisisiWorksheet::className(), ['ID' => 'WorksheetID']);
    }


    
}
