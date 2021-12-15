<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "master_kependudukan".
 *
 * @property integer $id
 * @property string $nomorkk
 * @property string $nik
 * @property string $namalengkap
 * @property string $al1
 * @property string $rt
 * @property string $rw
 * @property string $kodekec
 * @property string $kodekel
 * @property string $alamat
 * @property string $lhrtempat
 * @property string $lhrtanggal
 * @property string $ttl
 * @property string $umur
 * @property integer $jk
 * @property string $jenis
 * @property integer $status
 * @property string $sts
 * @property string $hub
 * @property integer $agama
 * @property string $agm
 * @property string $pendidikan
 * @property string $pekerjaan
 * @property string $klain_fisik
 * @property string $aktalhr
 * @property string $aktakawin
 * @property string $aktacerai
 * @property string $nocacat
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class MasterKependudukan extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'master_kependudukan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nik', 'namalengkap'], 'required'],
            [['jk', 'status', 'agama', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['nomorkk', 'nik', 'rt', 'rw', 'kodekec', 'kodekel', 'lhrtempat', 'lhrtanggal', 'ttl', 'umur', 'jenis', 'sts', 'hub', 'agm', 'pendidikan', 'pekerjaan', 'klain_fisik', 'aktalhr', 'aktakawin', 'aktacerai', 'nocacat'], 'string', 'max' => 50],
            [['namalengkap', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['al1', 'alamat'], 'string', 'max' => 255],
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
            'nomorkk' => Yii::t('app', 'Nomorkk'),
            'nik' => Yii::t('app', 'Nik'),
            'namalengkap' => Yii::t('app', 'Namalengkap'),
            'al1' => Yii::t('app', 'Al1'),
            'rt' => Yii::t('app', 'Rt'),
            'rw' => Yii::t('app', 'Rw'),
            'kodekec' => Yii::t('app', 'Kodekec'),
            'kodekel' => Yii::t('app', 'Kodekel'),
            'alamat' => Yii::t('app', 'Alamat'),
            'lhrtempat' => Yii::t('app', 'Lhrtempat'),
            'lhrtanggal' => Yii::t('app', 'Lhrtanggal'),
            'ttl' => Yii::t('app', 'Ttl'),
            'umur' => Yii::t('app', 'Umur'),
            'jk' => Yii::t('app', 'Jk'),
            'jenis' => Yii::t('app', 'Jenis'),
            'status' => Yii::t('app', 'Status'),
            'sts' => Yii::t('app', 'Sts'),
            'hub' => Yii::t('app', 'Hub'),
            'agama' => Yii::t('app', 'Agama'),
            'agm' => Yii::t('app', 'Agm'),
            'pendidikan' => Yii::t('app', 'Pendidikan'),
            'pekerjaan' => Yii::t('app', 'Pekerjaan'),
            'klain_fisik' => Yii::t('app', 'Klain Fisik'),
            'aktalhr' => Yii::t('app', 'Aktalhr'),
            'aktakawin' => Yii::t('app', 'Aktakawin'),
            'aktacerai' => Yii::t('app', 'Aktacerai'),
            'nocacat' => Yii::t('app', 'Nocacat'),
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
