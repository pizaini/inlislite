<?php

namespace common\models;

use Yii;
use \common\models\base\Lockers as BaseLockers;

/**
 * This is the model class for table "lockers".
 */
class Lockers extends BaseLockers
{

	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [


            [['No_pinjaman','no_member','loker_id',], 'required'],
            [['No_pinjaman'], 'unique'],
            [['id_jamin_idt', 'id_jamin_uang', 'loker_id', 'id_pelanggaran_locker', 'CreateBy', 'UpdateBy'], 'integer'],
            [['tanggal_pinjam', 'tanggal_kembali', 'CreateDate', 'UpdateDate'], 'safe'],
            [['No_pinjaman'], 'string', 'max' => 255],
            [['no_member', 'no_identitas'], 'string', 'max' => 50],
            [['jenis_jaminan'], 'string', 'max' => 20],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['loker_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterLoker::className(), 'targetAttribute' => ['loker_id' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['id_jamin_idt'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJenisIdentitas::className(), 'targetAttribute' => ['id_jamin_idt' => 'id']],
            [['no_member'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['no_member' => 'MemberNo']],
            [['id_pelanggaran_locker'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPelanggaranLocker::className(), 'targetAttribute' => ['id_pelanggaran_locker' => 'ID']],
            [['id_jamin_uang'], 'exist', 'skipOnError' => true, 'targetClass' => MasterUangJaminan::className(), 'targetAttribute' => ['id_jamin_uang' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]



            // [['No_pinjaman','no_member','loker_id',], 'required'],
            // [['No_pinjaman'], 'unique'],
            // [['no_identitas', 'loker_id','id_pelanggaran_locker'], 'integer'],
            // [['tanggal_pinjam', 'tanggal_kembali', 'CreateDate', 'UpdateDate'], 'safe'],
            // [['No_pinjaman'], 'string', 'max' => 255],
            // [['jenis_jaminan'], 'string', 'max' => 20],
            // [['CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            // [['loker_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterLoker::className(), 'targetAttribute' => ['loker_id' => 'ID']],
            // [['id_jamin_uang'], 'exist', 'skipOnError' => true, 'targetClass' => MasterUangJaminan::className(), 'targetAttribute' => ['id_jamin_uang' => 'ID']],
            // [['id_jamin_idt'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJenisIdentitas::className(), 'targetAttribute' => ['id_jamin_idt' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'No_pinjaman' => Yii::t('app', 'No Pinjaman'),
            'no_identitas' => Yii::t('app', 'No Identitas'),
            'no_member' => Yii::t('app', 'No Member'),
            'jenis_jaminan' => Yii::t('app', 'Jenis Jaminan'),
            'loker_id' => Yii::t('app', 'Loker'),
            'id_jamin_uang' => Yii::t('app', 'Jaminan Uang'),
            'id_jamin_idt' => Yii::t('app', 'Jenis Jaminan Identitas'),
            'tanggal_pinjam' => Yii::t('app', 'Tanggal Pinjam'),
            'tanggal_kembali' => Yii::t('app', 'Tanggal Kembali'),
            'id_pelanggaran_locker' => Yii::t('app', 'Pelanggaran'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoker()
    {
        return $this->hasOne(\common\models\MasterLoker::className(), ['ID' => 'loker_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPelanggaran()
    {
        return $this->hasOne(\common\models\MasterPelanggaranLocker::className(), ['ID' => 'id_pelanggaran_locker']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getUangJaminan()
    {
        return $this->hasOne(\common\models\MasterUangJaminan::className(), ['ID' => 'id_jamin_uang']);
    }
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisIdentitas()
    {
        return $this->hasOne(\common\models\MasterJenisIdentitas::className(), ['id' => 'id_jamin_idt']);
    }

}
