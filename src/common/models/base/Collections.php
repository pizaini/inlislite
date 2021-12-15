<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "collections".
 *
 * @property double $ID
 * @property string $NomorBarcode
 * @property string $NoInduk
 * @property string $Currency
 * @property string $RFID
 * @property string $Price
 * @property string $PriceType
 * @property string $TanggalPengadaan
 * @property string $CallNumber
 * @property integer $Branch_id
 * @property double $Catalog_id
 * @property integer $Partner_id
 * @property integer $Location_id
 * @property integer $Rule_id
 * @property integer $Category_id
 * @property integer $Media_id
 * @property integer $Source_id
 * @property integer $Status_id
 * @property integer $Location_Library_id
 * @property string $Keterangan_Sumber
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property boolean $IsVerified
 * @property integer $QUARANTINEDBY
 * @property string $QUARANTINEDDATE
 * @property string $QUARANTINEDTERMINAL
 * @property boolean $ISREFERENSI
 * @property string $EDISISERIAL
 * @property integer $NOJILID
 * @property string $TANGGAL_TERBIT_EDISI_SERIAL
 * @property string $BAHAN_SERTAAN
 * @property string $KETERANGAN_LAIN
 * @property string $TGLENTRYJILID
 * @property string $IDJILID
 * @property string $NOMORPANGGILJILID
 * @property integer $ISOPAC
 * @property integer $JILIDCREATEBY
 * @property string $KIILastUploadDate
 * @property string $BookingMemberID
 * @property string $BookingExpiredDate
 *
 * @property \common\models\Bacaditempat[] $bacaditempats
 * @property \common\models\Collectionloanextends[] $collectionloanextends
 * @property \common\models\Collectionloanitems[] $collectionloanitems
 * @property \common\models\Branchs $branch
 * @property \common\models\Catalogs $catalog
 * @property \common\models\Collectioncategorys $category
 * @property \common\models\Users $createBy
 * @property \common\models\Currency $currency
 * @property \common\models\Users $jILIDCREATEBY
 * @property \common\models\Locations $location
 * @property \common\models\LocationLibrary $locationLibrary
 * @property \common\models\Collectionmedias $media
 * @property \common\models\Partners $partner
 * @property \common\models\Users $qUARANTINEDBY
 * @property \common\models\Collectionrules $rule
 * @property \common\models\Collectionsources $source
 * @property \common\models\Collectionstatus $status
 * @property \common\models\Users $updateBy
 * @property \common\models\KeranjangKoleksi[] $keranjangKoleksis
 * @property \common\models\Pelanggaran[] $pelanggarans
 * @property \common\models\Pengiriman[] $pengirimen
 * @property \common\models\Stockopnamedetail[] $stockopnamedetails
 * @property \common\models\SumbanganKoleksi[] $sumbanganKoleksis
 */
class Collections extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NomorBarcode'], 'required'],
            [['Price', 'Catalog_id'], 'number'],
            [['TanggalPengadaan', 'CreateDate', 'UpdateDate', 'QUARANTINEDDATE', 'TANGGAL_TERBIT_EDISI_SERIAL', 'TGLENTRYJILID', 'KIILastUploadDate', 'BookingExpiredDate'], 'safe'],
            [['Branch_id', 'Partner_id', 'Location_id', 'Rule_id', 'Category_id', 'Media_id', 'Source_id', 'Status_id', 'Location_Library_id', 'CreateBy', 'UpdateBy', 'QUARANTINEDBY', 'NOJILID', 'ISOPAC', 'JILIDCREATEBY', 'IsDeposit', 'ThnTerbitDeposit', 'deposit_ws_ID', 'deposit_kode_wilayah_ID'], 'integer'],
            [['IsVerified', 'ISREFERENSI'], 'boolean'],
            [['NomorBarcode', 'NoInduk', 'RFID', 'BookingMemberID'], 'string', 'max' => 50],
            [['Currency'], 'string', 'max' => 30],
            [['PriceType'], 'string', 'max' => 45],
            [['CallNumber', 'Keterangan_Sumber', 'EDISISERIAL', 'BAHAN_SERTAAN', 'KETERANGAN_LAIN'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal', 'QUARANTINEDTERMINAL', 'NOMORPANGGILJILID'], 'string', 'max' => 100],
            [['IDJILID'], 'string', 'max' => 20],
            [['NomorDeposit'], 'string', 'max' => 21],
            [['Nomor_Regis'], 'string', 'max' => 22],
            [['NomorBarcode'], 'unique'],
            [['Branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branchs::className(), 'targetAttribute' => ['Branch_id' => 'ID']],
            [['Catalog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['Catalog_id' => 'ID']],
            [['Category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectioncategorys::className(), 'targetAttribute' => ['Category_id' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['Currency'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['Currency' => 'Currency']],
            [['deposit_ws_ID'], 'exist', 'skipOnError' => true, 'targetClass' => DepositWs::className(), 'targetAttribute' => ['deposit_ws_ID' => 'ID']],
            [['JILIDCREATEBY'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['JILIDCREATEBY' => 'ID']],
            [['Location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['Location_id' => 'ID']],
            [['Location_Library_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationLibrary::className(), 'targetAttribute' => ['Location_Library_id' => 'ID']],
            [['Media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionmedias::className(), 'targetAttribute' => ['Media_id' => 'ID']],
            [['Partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partners::className(), 'targetAttribute' => ['Partner_id' => 'ID']],
            [['QUARANTINEDBY'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['QUARANTINEDBY' => 'ID']],
            [['Rule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionrules::className(), 'targetAttribute' => ['Rule_id' => 'ID']],
            [['Source_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionsources::className(), 'targetAttribute' => ['Source_id' => 'ID']],
            [['Status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionstatus::className(), 'targetAttribute' => ['Status_id' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'NomorBarcode' => Yii::t('app', 'Nomor Barcode'),
            'NoInduk' => Yii::t('app', 'No Induk'),
            'Currency' => Yii::t('app', 'Currency'),
            'RFID' => Yii::t('app', 'Rfid'),
            'Price' => Yii::t('app', 'Price'),
            'PriceType' => Yii::t('app', 'Price Type'),
            'TanggalPengadaan' => Yii::t('app', 'Tanggal Pengadaan'),
            'CallNumber' => Yii::t('app', 'Call Number'),
            'Branch_id' => Yii::t('app', 'Branch ID'),
            'Catalog_id' => Yii::t('app', 'Catalog ID'),
            'Partner_id' => Yii::t('app', 'Partner ID'),
            'Location_id' => Yii::t('app', 'Location ID'),
            'Rule_id' => Yii::t('app', 'Rule ID'),
            'Category_id' => Yii::t('app', 'Category ID'),
            'Media_id' => Yii::t('app', 'Media ID'),
            'Source_id' => Yii::t('app', 'Source ID'),
            'Status_id' => Yii::t('app', 'Status ID'),
            'Location_Library_id' => Yii::t('app', 'Location  Library ID'),
            'Keterangan_Sumber' => Yii::t('app', 'Keterangan  Sumber'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'IsVerified' => Yii::t('app', 'Is Verified'),
            'QUARANTINEDBY' => Yii::t('app', 'Quarantinedby'),
            'QUARANTINEDDATE' => Yii::t('app', 'Quarantineddate'),
            'QUARANTINEDTERMINAL' => Yii::t('app', 'Quarantinedterminal'),
            'ISREFERENSI' => Yii::t('app', 'Isreferensi'),
            'EDISISERIAL' => Yii::t('app', 'Edisiserial'),
            'NOJILID' => Yii::t('app', 'Nojilid'),
            'TANGGAL_TERBIT_EDISI_SERIAL' => Yii::t('app', 'Tanggal  Terbit  Edisi  Serial'),
            'BAHAN_SERTAAN' => Yii::t('app', 'Bahan  Sertaan'),
            'KETERANGAN_LAIN' => Yii::t('app', 'Keterangan  Lain'),
            'TGLENTRYJILID' => Yii::t('app', 'Tglentryjilid'),
            'IDJILID' => Yii::t('app', 'Idjilid'),
            'NOMORPANGGILJILID' => Yii::t('app', 'Nomorpanggiljilid'),
            'ISOPAC' => Yii::t('app', 'Isopac'),
            'JILIDCREATEBY' => Yii::t('app', 'Jilidcreateby'),
            'KIILastUploadDate' => Yii::t('app', 'Kiilast Upload Date'),
            'BookingMemberID' => Yii::t('app', 'Booking Member ID'),
            'BookingExpiredDate' => Yii::t('app', 'Booking Expired Date'),
            'IsDeposit' => Yii::t('app', 'Is Deposit'),
            'NomorDeposit' => Yii::t('app', 'Nomor Deposit'),
            'ThnTerbitDeposit' => Yii::t('app', 'Tahun Terbit Deposit'),
            'deposit_kode_wilayah_ID' => Yii::t('app', 'Deposit Kode Wilayah  ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBacaditempats()
    {
        return $this->hasMany(\common\models\Bacaditempat::className(), ['collection_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionloanextends()
    {
        return $this->hasMany(\common\models\Collectionloanextends::className(), ['Collection_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionloanitems()
    {
        return $this->hasMany(\common\models\Collectionloanitems::className(), ['Collection_id' => 'ID']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'CreateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\common\models\Currency::className(), ['Currency' => 'Currency']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepositWs()
    {
        return $this->hasOne(\common\models\DepositWs::className(), ['ID' => 'deposit_ws_ID']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeranjangKoleksis()
    {
        return $this->hasMany(\common\models\KeranjangKoleksi::className(), ['Collection_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPelanggarans()
    {
        return $this->hasMany(\common\models\Pelanggaran::className(), ['Collection_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengirimen()
    {
        return $this->hasMany(\common\models\Pengiriman::className(), ['Collection_ID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnamedetails()
    {
        return $this->hasMany(\common\models\Stockopnamedetail::className(), ['CollectionID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumbanganKoleksis()
    {
        return $this->hasMany(\common\models\SumbanganKoleksi::className(), ['Collection_id' => 'ID']);
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
        \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'CreateDate',
                'updatedAtAttribute' => 'UpdateDate',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'CreateBy',
                'updatedByAttribute' => 'UpdateBy',
            ],
            [
                'class' => TerminalBehavior::className(),
                'createdTerminalAttribute' => 'CreateTerminal',
                'updatedTerminalAttribute' => 'UpdateTerminal',
                'value' => \Yii::$app->request->userIP,
            ],
        ];
    }


    
}
