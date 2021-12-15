<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "akuisisi_raw_log".
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
 * @property integer $AkuisisiLogID
 * @property string $ReportedBy
 * @property string $ReportedDate
 * @property string $LoggedDate
 * @property string $Media
 * @property string $Penempatan
 * @property integer $Data_ID
 *
 * @property \common\models\AkuisisiData $data
 * @property \common\models\AkuisisiLog $akuisisiLog
 * @property \common\models\AkuisisiWorksheet $worksheet
 */
class AkuisisiRawLog extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'akuisisi_raw_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'WorksheetID', 'ReportedBy', 'ReportedDate', 'LoggedDate'], 'required'],
            [['ID', 'WorksheetID', 'AkuisisiLogID', 'Data_ID'], 'integer'],
            [['Harga'], 'number'],
            [['ReportedDate', 'LoggedDate'], 'safe'],
            [['Judul', 'AnakJudul', 'Pengarang', 'PengarangTambahan', 'BadanKoperasi', 'Penerbit', 'TempatTerbit', 'DeskripsiFisik', 'Skala', 'KeteranganSumber'], 'string', 'max' => 500],
            [['TahunTerbit', 'Edisi', 'NoKlas', 'ISBN', 'Sumber', 'Currency', 'ReportedBy', 'Media', 'Penempatan'], 'string', 'max' => 50],
            [['Catatan'], 'string', 'max' => 1000],
            [['Data_ID'], 'exist', 'skipOnError' => true, 'targetClass' => AkuisisiData::className(), 'targetAttribute' => ['Data_ID' => 'ID']],
            [['AkuisisiLogID'], 'exist', 'skipOnError' => true, 'targetClass' => AkuisisiLog::className(), 'targetAttribute' => ['AkuisisiLogID' => 'ID']],
            [['WorksheetID'], 'exist', 'skipOnError' => true, 'targetClass' => AkuisisiWorksheet::className(), 'targetAttribute' => ['WorksheetID' => 'ID']]
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
            'AkuisisiLogID' => Yii::t('app', 'Akuisisi Log ID'),
            'ReportedBy' => Yii::t('app', 'Reported By'),
            'ReportedDate' => Yii::t('app', 'Reported Date'),
            'LoggedDate' => Yii::t('app', 'Logged Date'),
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
    public function getAkuisisiLog()
    {
        return $this->hasOne(\common\models\AkuisisiLog::className(), ['ID' => 'AkuisisiLogID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheet()
    {
        return $this->hasOne(\common\models\AkuisisiWorksheet::className(), ['ID' => 'WorksheetID']);
    }


    
}
