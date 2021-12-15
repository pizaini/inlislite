<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "akuisisi".
 *
 * @property integer $ID
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
 * @property string $Currency
 * @property string $Harga
 * @property integer $Eksemplar
 * @property double $CatalogID
 * @property string $CreatedDate
 * @property string $Status
 * @property string $Media
 * @property string $Penempatan
 * @property integer $Data_ID
 *
 * @property \common\models\AkuisisiData $data
 * @property \common\models\AkuisisiWorksheet $worksheet
 * @property \common\models\Catalogs $catalog
 * @property \common\models\AkuisisiAction[] $akuisisiActions
 * @property \common\models\AkuisisiRaw[] $akuisisiRaws
 */
class Akuisisi extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'akuisisi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['WorksheetID', 'CreatedDate'], 'required'],
            [['WorksheetID', 'Eksemplar', 'Data_ID'], 'integer'],
            [['Harga', 'CatalogID'], 'number'],
            [['CreatedDate'], 'safe'],
            [['Judul', 'AnakJudul', 'Catatan'], 'string', 'max' => 1000],
            [['Pengarang', 'PengarangTambahan', 'BadanKoperasi', 'Penerbit', 'DeskripsiFisik', 'Skala', 'KeteranganSumber'], 'string', 'max' => 500],
            [['TempatTerbit'], 'string', 'max' => 200],
            [['TahunTerbit', 'Edisi', 'NoKlas', 'ISBN', 'Sumber', 'Currency', 'Status', 'Media', 'Penempatan'], 'string', 'max' => 50],
            [['Data_ID'], 'exist', 'skipOnError' => true, 'targetClass' => AkuisisiData::className(), 'targetAttribute' => ['Data_ID' => 'ID']],
            [['WorksheetID'], 'exist', 'skipOnError' => true, 'targetClass' => AkuisisiWorksheet::className(), 'targetAttribute' => ['WorksheetID' => 'ID']],
            [['CatalogID'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['CatalogID' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
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
            'Currency' => Yii::t('app', 'Currency'),
            'Harga' => Yii::t('app', 'Harga'),
            'Eksemplar' => Yii::t('app', 'Eksemplar'),
            'CatalogID' => Yii::t('app', 'Catalog ID'),
            'CreatedDate' => Yii::t('app', 'Created Date'),
            'Status' => Yii::t('app', 'Status'),
            'Media' => Yii::t('app', 'Media'),
            'Penempatan' => Yii::t('app', 'Penempatan'),
            'Data_ID' => Yii::t('app', 'Data  ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getData()
    {
        return $this->hasOne(\common\models\AkuisisiData::className(), ['ID' => 'Data_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheet()
    {
        return $this->hasOne(\common\models\AkuisisiWorksheet::className(), ['ID' => 'WorksheetID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(\common\models\Catalogs::className(), ['ID' => 'CatalogID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisiActions()
    {
        return $this->hasMany(\common\models\AkuisisiAction::className(), ['AkuisisiID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisiRaws()
    {
        return $this->hasMany(\common\models\AkuisisiRaw::className(), ['AkuisisiID' => 'ID']);
    }


    
}
