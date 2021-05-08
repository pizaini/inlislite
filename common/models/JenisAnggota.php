<?php

namespace common\models;

use Yii;
use \common\models\base\JenisAnggota as BaseJenisAnggota;

/**
 * This is the model class for table "jenis_anggota".
 */
class JenisAnggota extends BaseJenisAnggota
{

     /**
     * @inheritdoc
     */

    public function rules() 
    { 
        return [
            [['jenisanggota', 'MasaBerlakuAnggota'], 'required'],
            [['jenisanggota'], 'unique'],
            [['CreateBy', 'UpdateBy', 'MaxPinjamKoleksi', 'MaxLoanDays', 'DendaTenorMultiply', 'WarningLoanDueDay', 'DaySuspend', 'SuspendTenorMultiply', 'DayPerpanjang', 'CountPerpanjang'], 'integer'],
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

}
