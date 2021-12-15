<?php

namespace common\models;

use Yii;
use \common\models\base\Groupguesses as BaseGroupguesses;

/**
 * This is the model class for table "groupguesses".
 */
class Groupguesses extends BaseGroupguesses
{
	    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NamaKetua', 'AsalInstansi', 'AlamatInstansi','NomerTelponKetua'], 'required'],
            [['CountPersonel', 'CountPNS', 'CountPSwasta', 'CountPeneliti', 'CountGuru', 'CountDosen', 'CountPensiunan', 'CountTNI', 'CountWiraswasta', 'CountPelajar', 'CountMahasiswa', 'CountLainnya', 'CountSD', 'CountSMP', 'CountSMA', 'CountD1', 'CountD2', 'CountD3', 'CountS1', 'CountS2', 'CountS3', 'CountLaki', 'CountPerempuan', 'TujuanKunjungan_ID', 'Location_ID'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['NamaKetua', 'AsalInstansi', 'AlamatInstansi', 'EmailInstansi', 'Information'], 'string', 'max' => 255],
            [['NomerTelponKetua', 'TeleponInstansi'], 'string', 'max' => 20],
            [['CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Location_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['Location_ID' => 'ID']],
            [['TujuanKunjungan_ID'], 'exist', 'skipOnError' => true, 'targetClass' => TujuanKunjungan::className(), 'targetAttribute' => ['TujuanKunjungan_ID' => 'ID']],
            ['EmailInstansi', 'email', 'message' => 'Format email salah'],
        ];
    }
}
