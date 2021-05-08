<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "memberguesses".
 *
 * @property integer $ID
 * @property string $NoAnggota
 * @property string $Nama
 * @property integer $Status_id
 * @property integer $MasaBerlaku_id
 * @property integer $Profesi_id
 * @property integer $PendidikanTerakhir_id
 * @property integer $JenisKelamin_id
 * @property string $Alamat
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property string $Deskripsi
 * @property integer $LOCATIONLOANS_ID
 * @property integer $Location_Id
 * @property integer $TujuanKunjungan_Id
 * @property string $Information
 * @property string $NoPengunjung
 *
 * @property \common\models\TujuanKunjungan $tujuanKunjungan
 * @property \common\models\Users $createBy
 * @property \common\models\JenisKelamin $jenisKelamin
 * @property \common\models\Locations $location
 * @property \common\models\MasaBerlakuAnggota $masaBerlaku
 * @property \common\models\MasterPekerjaan $profesi
 * @property \common\models\MasterPendidikan $pendidikanTerakhir
 * @property \common\models\StatusAnggota $status
 * @property \common\models\Users $updateBy
 */
class Memberguesses extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'memberguesses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nama'], 'required'],
            [['Status_id', 'MasaBerlaku_id', 'Profesi_id', 'PendidikanTerakhir_id', 'JenisKelamin_id', 'CreateBy', 'UpdateBy', 'LOCATIONLOANS_ID', 'Location_Id', 'TujuanKunjungan_Id'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['NoAnggota', 'NoPengunjung'], 'string', 'max' => 50],
            [['Nama', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Alamat', 'Deskripsi', 'Information'], 'string', 'max' => 255],
            [['TujuanKunjungan_Id'], 'exist', 'skipOnError' => true, 'targetClass' => TujuanKunjungan::className(), 'targetAttribute' => ['TujuanKunjungan_Id' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['JenisKelamin_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisKelamin::className(), 'targetAttribute' => ['JenisKelamin_id' => 'ID']],
            [['Location_Id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['Location_Id' => 'ID']],
            [['MasaBerlaku_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasaBerlakuAnggota::className(), 'targetAttribute' => ['MasaBerlaku_id' => 'id']],
            [['Profesi_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPekerjaan::className(), 'targetAttribute' => ['Profesi_id' => 'id']],
            [['PendidikanTerakhir_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPendidikan::className(), 'targetAttribute' => ['PendidikanTerakhir_id' => 'id']],
            [['Status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusAnggota::className(), 'targetAttribute' => ['Status_id' => 'id']],
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
            'NoAnggota' => Yii::t('app', 'No Anggota'),
            'Nama' => Yii::t('app', 'Nama'),
            'Status_id' => Yii::t('app', 'Status ID'),
            'MasaBerlaku_id' => Yii::t('app', 'Masa Berlaku ID'),
            'Profesi_id' => Yii::t('app', 'Profesi ID'),
            'PendidikanTerakhir_id' => Yii::t('app', 'Pendidikan Terakhir ID'),
            'JenisKelamin_id' => Yii::t('app', 'Jenis Kelamin ID'),
            'Alamat' => Yii::t('app', 'Alamat'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'Deskripsi' => Yii::t('app', 'Deskripsi'),
            'LOCATIONLOANS_ID' => Yii::t('app', 'Locationloans  ID'),
            'Location_Id' => Yii::t('app', 'Location  ID'),
            'TujuanKunjungan_Id' => Yii::t('app', 'Tujuan Kunjungan  ID'),
            'Information' => Yii::t('app', 'Information'),
            'NoPengunjung' => Yii::t('app', 'No Pengunjung'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTujuanKunjungan()
    {
        return $this->hasOne(\common\models\TujuanKunjungan::className(), ['ID' => 'TujuanKunjungan_Id']);
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
    public function getJenisKelamin()
    {
        return $this->hasOne(\common\models\JenisKelamin::className(), ['ID' => 'JenisKelamin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(\common\models\Locations::className(), ['ID' => 'Location_Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasaBerlaku()
    {
        return $this->hasOne(\common\models\MasaBerlakuAnggota::className(), ['id' => 'MasaBerlaku_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesi()
    {
        return $this->hasOne(\common\models\MasterPekerjaan::className(), ['id' => 'Profesi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendidikanTerakhir()
    {
        return $this->hasOne(\common\models\MasterPendidikan::className(), ['id' => 'PendidikanTerakhir_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(\common\models\StatusAnggota::className(), ['id' => 'Status_id']);
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
