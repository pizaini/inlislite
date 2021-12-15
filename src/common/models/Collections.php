<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;
use \common\models\base\Collections as BaseCollections;

/**
 * This is the model class for table "collections".
 */
class Collections extends BaseCollections
{

	public $DataBib;
    public $JumlahEksemplar;
    public $ModeInput;
    public $TahunJilid;
    public $Eksemplar;
    public $Edisi_id;
    public $Title;
    public $Author;
    public $Publishment;
    public $PhysicalDescription;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['Price', 'Catalog_id'], 'number'],
            [[ 'NOJILID','TanggalPengadaan', 'CreateDate', 'UpdateDate', 'QUARANTINEDDATE', 'TANGGAL_TERBIT_EDISI_SERIAL', 'TGLENTRYJILID','BookingMemberID', 'BookingExpiredDate','Branch_id'], 'safe'],
            [['JumlahEksemplar', 'IsVerified', 'NOJILID','UpdateBy','CreateBy', 'QUARANTINEDBY', 'JILIDCREATEBY', 'ThnTerbitDeposit', 'deposit_ws_ID', 'deposit_kode_wilayah_ID'], 'integer'],
            [['JumlahEksemplar','NomorBarcode','TanggalPengadaan','Price', 'Currency','Partner_id', 'Location_Library_id','Location_id', 'Rule_id', 'Category_id', 'Media_id', 'Source_id', 'Status_id'], 'required'],
            [['ISREFERENSI', 'ISOPAC'], 'boolean'],
            [['NoInduk', 'RFID'], 'string', 'max' => 255],
            [['Currency'], 'string', 'max' => 30],
            [['PriceType'], 'string', 'max' => 45],
            [['CallNumber',  'CreateTerminal',  'UpdateTerminal', 'QUARANTINEDTERMINAL', 'NOMORPANGGILJILID'], 'string', 'max' => 100],
            [['NomorBarcode'], 'string', 'max' => 50],
            [['Keterangan_Sumber'], 'string', 'max' => 200],
            [['EDISISERIAL', 'BAHAN_SERTAAN', 'KETERANGAN_LAIN'], 'string', 'max' => 2000],
            [['IDJILID'], 'string', 'max' => 20],
            [['NomorDeposit'], 'string', 'max' => 21],
            [['Nomor_Regis'], 'string', 'max' => 22],
            [['NomorBarcode'], 'unique'],
            [['Rule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionrules::className(), 'targetAttribute' => ['Rule_id' => 'ID']],
            [['Media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionmedias::className(), 'targetAttribute' => ['Media_id' => 'ID']],
            [['Branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branchs::className(), 'targetAttribute' => ['Branch_id' => 'ID']],
            [['Catalog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['Catalog_id' => 'ID']],
            [['Partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partners::className(), 'targetAttribute' => ['Partner_id' => 'ID']],
            [['Location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['Location_id' => 'ID']],
            [['Source_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionsources::className(), 'targetAttribute' => ['Source_id' => 'ID']],
            [['Category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectioncategorys::className(), 'targetAttribute' => ['Category_id' => 'ID']],
            [['Location_Library_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationLibrary::className(), 'targetAttribute' => ['Location_Library_id' => 'ID']],
            [['Currency'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['Currency' => 'Currency']],
            [['Status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionstatus::className(), 'targetAttribute' => ['Status_id' => 'ID']]
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
            'BookingMemberID'  => Yii::t('app', 'coll_BookingMemberID'),
            'BookingExpiredDate'  => Yii::t('app', 'coll_BookingExpiredDate'),
            'Nomor_Regis'  => Yii::t('app', 'Nomor Regis'),
        ];
    }

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
            [
                'class'=>'common\components\behaviors\DateConverter',
                'physicalFormat'=>'Y-m-d',
                'attributes'=>[
                    'TglPengadaan' => 'TanggalPengadaan',
                    'TglTerbitEdisiSerial' => 'TANGGAL_TERBIT_EDISI_SERIAL',
                ]
            ],
        ];
    }

    public function getCollectionById($id)
    {
        $model = Collections::find()
                ->addSelect(["collections.*", "CONCAT('<b>',catalogs.Title,'</b>','<br/>'
                        ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                        ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                        ,' ',catalogs.PublishYear
                        ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                        ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                        ,'<br/>',worksheets.name
                        ) AS DataBib"])
                ->leftJoin('catalogs',' collections.Catalog_id = catalogs.id')
                ->leftJoin('worksheets',' catalogs.Worksheet_id = worksheets.id')
                ->joinWith('source')
                ->joinWith('media')
                ->joinWith('category')
                ->joinWith('rule')
                ->joinWith('location')
                ->joinWith('locationLibrary')
                ->joinWith('status')
                ->where(['collections.ID'=>$id])->one();
        return $model;

    }
    
    /** 
    * @return \yii\db\ActiveQuery 
    */ 
   public function getMember() 
   { 
       return $this->hasOne(\common\models\Members::className(), ['MemberNo' => 'BookingMemberID']); 
   }


}
