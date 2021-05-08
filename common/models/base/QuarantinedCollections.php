<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "quarantined_collections".
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
 * @property \common\models\Users $createBy
 * @property \common\models\Users $qUARANTINEDBY
 * @property \common\models\Users $updateBy
 */
class QuarantinedCollections extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quarantined_collections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID', 'Price', 'Catalog_id'], 'number'],
            [['TanggalPengadaan', 'CreateDate', 'UpdateDate', 'QUARANTINEDDATE', 'TANGGAL_TERBIT_EDISI_SERIAL', 'TGLENTRYJILID', 'KIILastUploadDate', 'BookingExpiredDate'], 'safe'],
            [['Branch_id', 'Partner_id', 'Location_id', 'Rule_id', 'Category_id', 'Media_id', 'Source_id', 'Status_id', 'Location_Library_id', 'CreateBy', 'UpdateBy', 'QUARANTINEDBY', 'NOJILID', 'ISOPAC', 'JILIDCREATEBY'], 'integer'],
            [['IsVerified', 'ISREFERENSI'], 'boolean'],
            [['NomorBarcode', 'NoInduk', 'RFID', 'BookingMemberID'], 'string', 'max' => 50],
            [['Currency'], 'string', 'max' => 30],
            [['PriceType'], 'string', 'max' => 45],
            [['CallNumber', 'Keterangan_Sumber', 'EDISISERIAL', 'BAHAN_SERTAAN', 'KETERANGAN_LAIN'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal', 'QUARANTINEDTERMINAL', 'NOMORPANGGILJILID'], 'string', 'max' => 100],
            [['IDJILID'], 'string', 'max' => 20],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['QUARANTINEDBY'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['QUARANTINEDBY' => 'ID']],
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
        ];
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
    public function getQUARANTINEDBY()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'QUARANTINEDBY']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
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
