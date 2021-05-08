<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "lockers".
 *
 * @property integer $ID
 * @property string $No_pinjaman
 * @property string $no_member
 * @property string $no_identitas
 * @property string $jenis_jaminan
 * @property integer $id_jamin_idt
 * @property integer $id_jamin_uang
 * @property integer $loker_id
 * @property string $tanggal_pinjam
 * @property string $tanggal_kembali
 * @property integer $id_pelanggaran_locker
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\MasterLoker $loker
 * @property \common\models\Users $createBy
 * @property \common\models\MasterJenisIdentitas $idJaminIdt
 * @property \common\models\Members $noMember
 * @property \common\models\MasterPelanggaranLocker $idPelanggaranLocker
 * @property \common\models\MasterUangJaminan $idJaminUang
 * @property \common\models\Users $updateBy
 */
class Lockers extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lockers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['No_pinjaman'], 'required'],
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
            'no_member' => Yii::t('app', 'No Member'),
            'no_identitas' => Yii::t('app', 'No Identitas'),
            'jenis_jaminan' => Yii::t('app', 'Jenis Jaminan'),
            'id_jamin_idt' => Yii::t('app', 'Id Jamin Idt'),
            'id_jamin_uang' => Yii::t('app', 'Id Jamin Uang'),
            'loker_id' => Yii::t('app', 'Loker ID'),
            'tanggal_pinjam' => Yii::t('app', 'Tanggal Pinjam'),
            'tanggal_kembali' => Yii::t('app', 'Tanggal Kembali'),
            'id_pelanggaran_locker' => Yii::t('app', 'Id Pelanggaran Locker'),
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
    public function getCreateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'CreateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdJaminIdt()
    {
        return $this->hasOne(\common\models\MasterJenisIdentitas::className(), ['id' => 'id_jamin_idt']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoMember()
    {
        return $this->hasOne(\common\models\Members::className(), ['MemberNo' => 'no_member']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPelanggaranLocker()
    {
        return $this->hasOne(\common\models\MasterPelanggaranLocker::className(), ['ID' => 'id_pelanggaran_locker']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdJaminUang()
    {
        return $this->hasOne(\common\models\MasterUangJaminan::className(), ['ID' => 'id_jamin_uang']);
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
