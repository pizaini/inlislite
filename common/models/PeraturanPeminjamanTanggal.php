<?php

namespace common\models;

use Yii;
use \common\models\base\PeraturanPeminjamanTanggal as BasePeraturanPeminjamanTanggal;

/**
 * This is the model class for table "peraturan_peminjaman_tanggal".
 */
class PeraturanPeminjamanTanggal extends BasePeraturanPeminjamanTanggal
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TanggalAwal', 'TanggalAkhir', 'CreateDate', 'UpdateDate'], 'safe'],
            ['TanggalAwal', 'unique'],
            ['TanggalAkhir', 'unique'],
            [['TanggalAwal'], 'validateTanggalAwal'],
            [['TanggalAkhir'], 'validateTanggalAkhir'],
            [['CreateBy', 'UpdateBy', 'MaxPinjamKoleksi', 'MaxLoanDays', 'DendaTenorMultiply', 'WarningLoanDueDay', 'DaySuspend', 'SuspendTenorMultiply', 'DayPerpanjang', 'CountPerpanjang'], 'integer'],
            [['DendaTenorJumlah', 'DendaPerTenor', 'SuspendTenorJumlah'], 'number'],
            [['SuspendMember'], 'boolean'],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['DendaType', 'DendaTenorSatuan', 'SuspendType', 'SuspendTenorSatuan'], 'string', 'max' => 45],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }


    /**
     * [validateTanggalAwal description]
     * @return [type] [description]
     */
    public function validateTanggalAwal()
    {

    	// $model = PeraturanPeminjamanTanggal::find()->select('TanggalAkhir')->orderBy('TanggalAkhir DESC')->asArray()->one();

     //    if ($this->TanggalAwal <= $model['TanggalAkhir']) {
     //        $this->addError("TanggalAwal",Yii::t('app','Tanggal Awal harus lebih besar dari max tanggal akhir yang sudah ada'));
     //    }

    }

    /**
     * [validateTanggalAkhir description]
     * @return [type] [description]
     */
    public function validateTanggalAkhir()
    {
        if ($this->TanggalAwal >= $this->TanggalAkhir) {
            $this->addError("TanggalAkhir",Yii::t('app','Tanggal Akhir harus lebih besar dari Tanggal Awal'));
        }

    }






}
