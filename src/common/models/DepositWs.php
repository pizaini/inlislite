<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%deposit_ws}}".
 *
 * @property integer $ID
 * @property string $jenis_penerbit
 * @property integer $id_group_deposit_group_ws
 * @property integer $id_deposit_kelompok_penerbit_ws
 * @property string $nama_penerbit
 * @property string $alamat1
 * @property string $alamat2
 * @property string $alamat3
 * @property string $kabupaten
 * @property integer $ID_deposit_kode_wilayah
 * @property integer $kode_pos
 * @property integer $no_telp1
 * @property integer $no_telp2
 * @property integer $no_telp3
 * @property integer $no_fax
 * @property string $email
 * @property string $contact_person
 * @property integer $no_contact
 * @property integer $koleksi_per_tahun
 * @property string $keterangan
 * @property integer $status
 */
class DepositWs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%deposit_ws}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_group_deposit_group_ws', 'id_deposit_kelompok_penerbit_ws', 'ID_deposit_kode_wilayah', 'kode_pos', 'no_telp1', 'no_telp2', 'no_telp3', 'no_fax', 'no_contact', 'koleksi_per_tahun', 'status'], 'integer'],
            [['id_group_deposit_group_ws'], 'required'],
            [['jenis_penerbit', 'nama_penerbit'], 'string', 'max' => 55],
            [['alamat1', 'alamat2', 'alamat3', 'kabupaten'], 'string', 'max' => 65],
            [['email'], 'string', 'max' => 30],
            [['contact_person', 'keterangan'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'jenis_penerbit' => Yii::t('app', 'Jenis Penerbit'),
            'id_group_deposit_group_ws' => Yii::t('app', 'Id Deposit Group Penerbit Ws'),
            'id_deposit_kelompok_penerbit_ws' => Yii::t('app', 'Id Deposit Kelompok Penerbit Ws'),
            'nama_penerbit' => Yii::t('app', 'Nama Penerbit'),
            'alamat1' => Yii::t('app', 'Alamat1'),
            'alamat2' => Yii::t('app', 'Alamat2'),
            'alamat3' => Yii::t('app', 'Alamat3'),
            'kabupaten' => Yii::t('app', 'Kabupaten'),
            'ID_deposit_kode_wilayah' => Yii::t('app', 'Id Wilayah Ws'),
            'kode_pos' => Yii::t('app', 'Kode Pos'),
            'no_telp1' => Yii::t('app', 'No Telp1'),
            'no_telp2' => Yii::t('app', 'No Telp2'),
            'no_telp3' => Yii::t('app', 'No Telp3'),
            'no_fax' => Yii::t('app', 'No Fax'),
            'email' => Yii::t('app', 'Email'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'no_contact' => Yii::t('app', 'No Contact'),
            'koleksi_per_tahun' => Yii::t('app', 'Koleksi Per Tahun'),
            'keterangan' => Yii::t('app', 'Keterangan'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getCollections() 
    { 
        return $this->hasMany(Collections::className(), ['deposit_ws_ID' => 'ID']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getDepositKelompokPenerbit() 
    { 
        return $this->hasOne(DepositKelompokPenerbit::className(), ['ID' => 'id_deposit_kelompok_penerbit_ws']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getDepositKodeWilayah() 
    { 
        return $this->hasOne(DepositKodeWilayah::className(), ['ID' => 'ID_deposit_kode_wilayah']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getGroupDepositGroup() 
    { 
        return $this->hasOne(DepositGroupWs::className(), ['id_group' => 'id_group_deposit_group_ws']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getLetters() 
    { 
        return $this->hasMany(Letter::className(), ['PUBLISHER_ID' => 'ID']);
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
        ];
    }
}
