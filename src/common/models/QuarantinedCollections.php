<?php

namespace common\models;

use Yii;
use \common\models\base\QuarantinedCollections as BaseQuarantinedCollections;

/**
 * This is the model class for table "quarantined_collections".
 */
class QuarantinedCollections extends BaseQuarantinedCollections
{

	public $DataBib;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branchs::className(), 'targetAttribute' => ['Branch_id' => 'ID']],
            [['Catalog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['Catalog_id' => 'ID']],
            [['Category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectioncategorys::className(), 'targetAttribute' => ['Category_id' => 'ID']],
            [['Currency'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['Currency' => 'Currency']],
            [['JILIDCREATEBY'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['JILIDCREATEBY' => 'ID']],
            [['Location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['Location_id' => 'ID']],
            [['Media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionmedias::className(), 'targetAttribute' => ['Media_id' => 'ID']],
            [['Partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partners::className(), 'targetAttribute' => ['Partner_id' => 'ID']],
            [['QUARANTINEDBY'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['QUARANTINEDBY' => 'ID']],
            [['Rule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionrules::className(), 'targetAttribute' => ['Rule_id' => 'ID']],
            [['Source_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionsources::className(), 'targetAttribute' => ['Source_id' => 'ID']],
            [['Location_Library_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationLibrary::className(), 'targetAttribute' => ['Location_Library_id' => 'ID']],
            [['Status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionstatus::className(), 'targetAttribute' => ['Status_id' => 'ID']],
        ];
    }

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'coll_coll_ID'),
            'NoInduk' => Yii::t('app', 'coll_No Induk'),
            'Currency' => Yii::t('app', 'coll_Currency'),
            'RFID' => Yii::t('app', 'coll_Rfid'),
            'Price' => Yii::t('app', 'coll_Price'),
            'PriceType' => Yii::t('app', 'Price Type'),
            'TanggalPengadaan' => Yii::t('app', 'coll_Tanggal Pengadaan'),
            'CallNumber' => Yii::t('app', 'coll_Call Number'),
            'Branch_id' => Yii::t('app', 'coll_Branch ID'),
            'Catalog_id' => Yii::t('app', 'coll_Catalog ID'),
            'Partner_id' => Yii::t('app', 'coll_Partner ID'),
            'Location_id' => Yii::t('app', 'coll_Location ID'),
            'Rule_id' => Yii::t('app', 'coll_Rule ID'),
            'Category_id' => Yii::t('app', 'coll_Category ID'),
            'Media_id' => Yii::t('app', 'coll_Media ID'),
            'Source_id' => Yii::t('app', 'coll_Source ID'),
            'NomorBarcode' => Yii::t('app', 'coll_Nomor Barcode'),
            'Status_id' => Yii::t('app', 'coll_Status'),
            'Location_Library_id' => Yii::t('app', 'coll_Location Library ID'),
            'Keterangan_Sumber' => Yii::t('app', 'coll_Keterangan  Sumber'),
            'CreateBy' => Yii::t('app', 'coll_Create By'),
            'CreateDate' => Yii::t('app', 'coll_Create Date'),
            'CreateTerminal' => Yii::t('app', 'coll_Create Terminal'),
            'UpdateBy' => Yii::t('app', 'coll_Update By'),
            'UpdateDate' => Yii::t('app', 'coll_Update Date'),
            'UpdateTerminal' => Yii::t('app', 'coll_Update Terminal'),
            'IsVerified' => Yii::t('app', 'coll_Is Verified'),
            'QUARANTINEDBY' => Yii::t('app', 'coll_Quarantinedby'),
            'QUARANTINEDDATE' => Yii::t('app', 'coll_Quarantineddate'),
            'QUARANTINEDTERMINAL' => Yii::t('app', 'coll_Quarantinedterminal'),
            'ISREFERENSI' => Yii::t('app', 'coll_Isreferensi'),
            'EDISISERIAL' => Yii::t('app', 'coll_Edisiserial'),
            'NOJILID' => Yii::t('app', 'coll_Nojilid'),
            'TANGGAL_TERBIT_EDISI_SERIAL' => Yii::t('app', 'coll_Tanggal  Terbit  Edisi  Serial'),
            'BAHAN_SERTAAN' => Yii::t('app', 'coll_Bahan  Sertaan'),
            'KETERANGAN_LAIN' => Yii::t('app', 'coll_Keterangan  Lain'),
            'TGLENTRYJILID' => Yii::t('app', 'coll_Tglentryjilid'),
            'IDJILID' => Yii::t('app', 'coll_Idjilid'),
            'NOMORPANGGILJILID' => Yii::t('app', 'coll_Nomorpanggiljilid'),
            'ISOPAC' => Yii::t('app', 'coll_Isopac'),
            'JILIDCREATEBY' => Yii::t('app', 'coll_Jilidcreateby'),
            'DataBib' => Yii::t('app', 'coll_Bibliografis'),
            'JumlahEksemplar' => Yii::t('app', 'coll_JumlahEksemplar'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(\common\models\Branchs::className(), ['ID' => 'Branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(\common\models\Catalogs::className(), ['ID' => 'Catalog_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(\common\models\Collectioncategorys::className(), ['ID' => 'Category_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(\common\models\Currency::className(), ['Currency' => 'Currency']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJILIDCREATEBY()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'JILIDCREATEBY']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(\common\models\Locations::className(), ['ID' => 'Location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationLibrary()
    {
        return $this->hasOne(\common\models\LocationLibrary::className(), ['ID' => 'Location_Library_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(\common\models\Collectionmedias::className(), ['ID' => 'Media_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(\common\models\Partners::className(), ['ID' => 'Partner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQUARANTINEDBY()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'QUARANTINEDBY']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRule()
    {
        return $this->hasOne(\common\models\Collectionrules::className(), ['ID' => 'Rule_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(\common\models\Collectionsources::className(), ['ID' => 'Source_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(\common\models\Collectionstatus::className(), ['ID' => 'Status_id']);
    }

    
}
