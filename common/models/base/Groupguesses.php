<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "groupguesses".
 *
 * @property integer $ID
 * @property string $NamaKetua
 * @property string $NomerTelponKetua
 * @property string $AsalInstansi
 * @property string $AlamatInstansi
 * @property integer $CountPersonel
 * @property integer $CountPNS
 * @property integer $CountPSwasta
 * @property integer $CountPeneliti
 * @property integer $CountGuru
 * @property integer $CountDosen
 * @property integer $CountPensiunan
 * @property integer $CountTNI
 * @property integer $CountWiraswasta
 * @property integer $CountPelajar
 * @property integer $CountMahasiswa
 * @property integer $CountLainnya
 * @property integer $CountSD
 * @property integer $CountSMP
 * @property integer $CountSMA
 * @property integer $CountD1
 * @property integer $CountD2
 * @property integer $CountD3
 * @property integer $CountS1
 * @property integer $CountS2
 * @property integer $CountS3
 * @property integer $CountLaki
 * @property integer $CountPerempuan
 * @property integer $TujuanKunjungan_ID
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property integer $LocationLoans_ID
 * @property integer $Location_ID
 * @property string $TeleponInstansi
 * @property string $EmailInstansi
 * @property string $Information
 * @property string $NoPengunjung
 *
 * @property \common\models\Users $createBy
 * @property \common\models\Locations $location
 * @property \common\models\LocationLibrary $locationLoans
 * @property \common\models\TujuanKunjungan $tujuanKunjungan
 * @property \common\models\Users $updateBy
 */
class Groupguesses extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groupguesses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NamaKetua', 'AsalInstansi', 'AlamatInstansi'], 'required'],
            [['CountPersonel', 'CountPNS', 'CountPSwasta', 'CountPeneliti', 'CountGuru', 'CountDosen', 'CountPensiunan', 'CountTNI', 'CountWiraswasta', 'CountPelajar', 'CountMahasiswa', 'CountLainnya', 'CountSD', 'CountSMP', 'CountSMA', 'CountD1', 'CountD2', 'CountD3', 'CountS1', 'CountS2', 'CountS3', 'CountLaki', 'CountPerempuan', 'TujuanKunjungan_ID', 'CreateBy', 'UpdateBy', 'LocationLoans_ID', 'Location_ID'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['NamaKetua', 'AsalInstansi', 'AlamatInstansi', 'EmailInstansi', 'Information'], 'string', 'max' => 255],
            [['NomerTelponKetua', 'TeleponInstansi'], 'string', 'max' => 20],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['NoPengunjung'], 'string', 'max' => 50],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['Location_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['Location_ID' => 'ID']],
            [['LocationLoans_ID'], 'exist', 'skipOnError' => true, 'targetClass' => LocationLibrary::className(), 'targetAttribute' => ['LocationLoans_ID' => 'ID']],
            [['TujuanKunjungan_ID'], 'exist', 'skipOnError' => true, 'targetClass' => TujuanKunjungan::className(), 'targetAttribute' => ['TujuanKunjungan_ID' => 'ID']],
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
            'NamaKetua' => Yii::t('app', 'Nama Ketua'),
            'NomerTelponKetua' => Yii::t('app', 'Nomer Telpon Ketua'),
            'AsalInstansi' => Yii::t('app', 'Asal Instansi'),
            'AlamatInstansi' => Yii::t('app', 'Alamat Instansi'),
            'CountPersonel' => Yii::t('app', 'Count Personel'),
            'CountPNS' => Yii::t('app', 'Count Pns'),
            'CountPSwasta' => Yii::t('app', 'Count Pswasta'),
            'CountPeneliti' => Yii::t('app', 'Count Peneliti'),
            'CountGuru' => Yii::t('app', 'Count Guru'),
            'CountDosen' => Yii::t('app', 'Count Dosen'),
            'CountPensiunan' => Yii::t('app', 'Count Pensiunan'),
            'CountTNI' => Yii::t('app', 'Count Tni'),
            'CountWiraswasta' => Yii::t('app', 'Count Wiraswasta'),
            'CountPelajar' => Yii::t('app', 'Count Pelajar'),
            'CountMahasiswa' => Yii::t('app', 'Count Mahasiswa'),
            'CountLainnya' => Yii::t('app', 'Count Lainnya'),
            'CountSD' => Yii::t('app', 'Count Sd'),
            'CountSMP' => Yii::t('app', 'Count Smp'),
            'CountSMA' => Yii::t('app', 'Count Sma'),
            'CountD1' => Yii::t('app', 'Count D1'),
            'CountD2' => Yii::t('app', 'Count D2'),
            'CountD3' => Yii::t('app', 'Count D3'),
            'CountS1' => Yii::t('app', 'Count S1'),
            'CountS2' => Yii::t('app', 'Count S2'),
            'CountS3' => Yii::t('app', 'Count S3'),
            'CountLaki' => Yii::t('app', 'Count Laki'),
            'CountPerempuan' => Yii::t('app', 'Count Perempuan'),
            'TujuanKunjungan_ID' => Yii::t('app', 'Tujuan Kunjungan  ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'LocationLoans_ID' => Yii::t('app', 'Location Loans  ID'),
            'Location_ID' => Yii::t('app', 'Location  ID'),
            'TeleponInstansi' => Yii::t('app', 'Telepon Instansi'),
            'EmailInstansi' => Yii::t('app', 'Email Instansi'),
            'Information' => Yii::t('app', 'Information'),
            'NoPengunjung' => Yii::t('app', 'No Pengunjung'),
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
    public function getLocation()
    {
        return $this->hasOne(\common\models\Locations::className(), ['ID' => 'Location_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationLoans()
    {
        return $this->hasOne(\common\models\LocationLibrary::className(), ['ID' => 'LocationLoans_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTujuanKunjungan()
    {
        return $this->hasOne(\common\models\TujuanKunjungan::className(), ['ID' => 'TujuanKunjungan_ID']);
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
