<?php

namespace common\models;

use Yii;
use \common\models\base\MasterKependudukan as BaseMasterKependudukan;

/**
 * This is the model class for table "master_kependudukan".
 */
class MasterKependudukan extends BaseMasterKependudukan
{
	    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nomorkk' => Yii::t('app', 'Nomor KK'),
            'nik' => Yii::t('app', 'NIK'),
            'namalengkap' => Yii::t('app', 'Nama Lengkap'),
            'nama_ibu' => Yii::t('app', 'Nama Ibu'),
            'al1' => Yii::t('app', 'Al1'),
            'rt' => Yii::t('app', 'RT'),
            'rw' => Yii::t('app', 'RW'),
            'kodekec' => Yii::t('app', 'Kode Kecamatan'),
            'kodekel' => Yii::t('app', 'Kode Kelurahan'),
            'nama_kec' => Yii::t('app', 'Nama Kecamatan'),
            'nama_kel' => Yii::t('app', 'Nama Kelurahan'),
            'nama_kab' => Yii::t('app', 'Nama Kabupaten'),
            'nama_prov' => Yii::t('app', 'Nama Provinsi'),
            'alamat' => Yii::t('app', 'Alamat'),
            'lhrtempat' => Yii::t('app', 'Tempat Lahir'),
            'lhrtanggal' => Yii::t('app', 'Tgl Lahir'),
            'ttl' => Yii::t('app', 'Ttl'),
            'umur' => Yii::t('app', 'Umur'),
            'jk' => Yii::t('app', 'Jk'),
            'jenis' => Yii::t('app', 'Jenis Kelamin'),
            'status' => Yii::t('app', 'Status'),
            'sts' => Yii::t('app', 'Status Perkawinan'),
            'hub' => Yii::t('app', 'Status Hubungan Keluarga'),
            'agama' => Yii::t('app', 'Agama'),
            'agm' => Yii::t('app', 'Agm'),
            'pendidikan' => Yii::t('app', 'Pendidikan'),
            'pekerjaan' => Yii::t('app', 'Pekerjaan'),
            'klain_fisik' => Yii::t('app', 'Klain Fisik'),
            'aktalhr' => Yii::t('app', 'Aktalhr'),
            'aktakawin' => Yii::t('app', 'Akta Kawin'),
            'aktacerai' => Yii::t('app', 'Akta Cerai'),
            'nocacat' => Yii::t('app', 'Nocacat'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
        ];
    }

    public function rules()
    {
        return [
            [['nik', 'namalengkap'], 'required'],
            [['nik', 'nomorkk'], 'unique'],
            [['jk', 'status', 'agama', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['nomorkk', 'nik', 'nama_ibu', 'rt', 'rw', 'kodekec', 'kodekel', 'nama_kec','nama_kel','nama_kab','nama_prov','lhrtempat', 'lhrtanggal', 'ttl', 'umur', 'jenis', 'sts', 'hub', 'agm', 'pendidikan', 'pekerjaan', 'klain_fisik', 'aktalhr', 'aktakawin', 'aktacerai', 'nocacat'], 'string', 'max' => 50],
            [['namalengkap', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['al1', 'alamat'], 'string', 'max' => 255],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgamakep()
    {
        return $this->hasOne(\common\models\Agama::className(), ['ID' => 'agama']);
    }

}
