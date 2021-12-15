<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "jenis_anggota".
 *
 * @property integer $id
 * @property string $jenisanggota
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property string $BiayaPendaftaran
 * @property string $BiayaPerpanjangan
 * @property integer $MaxPinjamKoleksi
 * @property boolean $UploadDokumenKeanggotaanOnline
 * @property integer $MaxLoanDays
 * @property double $DendaTenorJumlah
 * @property string $DendaTenorSatuan
 * @property string $DendaPerTenor
 * @property integer $DendaTenorMultiply
 * @property boolean $SuspendMember
 * @property integer $WarningLoanDueDay
 * @property integer $DaySuspend
 * @property integer $DayPerpanjang
 * @property integer $CountPerpanjang
 * @property string $KIILastUploadDate
 *
 * @property \common\models\Collectioncategorysdefault[] $collectioncategorysdefaults
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 * @property \common\models\LocationLibraryDefault[] $locationLibraryDefaults
 * @property \common\models\Members[] $members
 */
class JenisAnggota extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenis_anggota';
    }

    /**
     * @inheritdoc
     */
    public function rules() 
    { 
        return [
            [['jenisanggota'], 'required'],
            [['MasaBerlakuAnggota', 'CreateBy', 'UpdateBy', 'MaxPinjamKoleksi', 'MaxLoanDays', 'DendaTenorMultiply', 'WarningLoanDueDay', 'DaySuspend', 'SuspendTenorMultiply', 'DayPerpanjang', 'CountPerpanjang'], 'integer'],
            [['CreateDate', 'UpdateDate', 'KIILastUploadDate'], 'safe'],
            [['BiayaPendaftaran', 'BiayaPerpanjangan', 'DendaTenorJumlah', 'DendaPerTenor', 'SuspendTenorJumlah'], 'number'],
            [['UploadDokumenKeanggotaanOnline', 'SuspendMember'], 'boolean'],
            [['jenisanggota'], 'string', 'max' => 50],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['DendaType', 'DendaTenorSatuan', 'SuspendType', 'SuspendTenorSatuan'], 'string', 'max' => 45],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => Yii::t('app', 'ID'),
            'jenisanggota' => Yii::t('app', 'Jenisanggota'),
            'MasaBerlakuAnggota' => Yii::t('app', 'Masa Berlaku Anggota'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'BiayaPendaftaran' => Yii::t('app', 'Biaya Pendaftaran'),
            'BiayaPerpanjangan' => Yii::t('app', 'Biaya Perpanjangan'),
            'UploadDokumenKeanggotaanOnline' => Yii::t('app', 'Upload Dokumen Keanggotaan Online'),
            'MaxPinjamKoleksi' => Yii::t('app', 'Max Pinjam Koleksi'),
            'MaxLoanDays' => Yii::t('app', 'Max Loan Days'),
            'DendaType' => Yii::t('app', 'Denda Type'),
            'DendaTenorJumlah' => Yii::t('app', 'Denda Tenor Jumlah'),
            'DendaTenorSatuan' => Yii::t('app', 'Denda Tenor Satuan'),
            'DendaPerTenor' => Yii::t('app', 'Denda Per Tenor'),
            'DendaTenorMultiply' => Yii::t('app', 'Denda Tenor Multiply'),
            'SuspendMember' => Yii::t('app', 'Suspend Member'),
            'WarningLoanDueDay' => Yii::t('app', 'Warning Loan Due Day'),
            'SuspendType' => Yii::t('app', 'Suspend Type'),
            'SuspendTenorJumlah' => Yii::t('app', 'Suspend Tenor Jumlah'),
            'SuspendTenorSatuan' => Yii::t('app', 'Suspend Tenor Satuan'),
            'DaySuspend' => Yii::t('app', 'Day Suspend'),
            'SuspendTenorMultiply' => Yii::t('app', 'Suspend Tenor Multiply'),
            'DayPerpanjang' => Yii::t('app', 'Day Perpanjang'),
            'CountPerpanjang' => Yii::t('app', 'Count Perpanjang'),
            'KIILastUploadDate' => Yii::t('app', 'Kiilast Upload Date'),
        ]; 
    } 


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectioncategorysdefaults()
    {
        return $this->hasMany(\common\models\Collectioncategorysdefault::className(), ['JenisAnggota_id' => 'id']);
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
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationLibraryDefaults()
    {
        return $this->hasMany(\common\models\LocationLibraryDefault::className(), ['JenisAnggota_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(\common\models\Members::className(), ['JenisAnggota_id' => 'id']);
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
