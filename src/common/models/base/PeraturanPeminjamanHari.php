<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "peraturan_peminjaman_hari".
 *
 * @property integer $ID
 * @property integer $DayIndex
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property integer $MaxPinjamKoleksi
 * @property integer $MaxLoanDays
 * @property string $DendaType
 * @property double $DendaTenorJumlah
 * @property string $DendaTenorSatuan
 * @property string $DendaPerTenor
 * @property integer $DendaTenorMultiply
 * @property boolean $SuspendMember
 * @property integer $WarningLoanDueDay
 * @property string $SuspendType
 * @property double $SuspendTenorJumlah
 * @property string $SuspendTenorSatuan
 * @property integer $DaySuspend
 * @property integer $SuspendTenorMultiply
 * @property integer $DayPerpanjang
 * @property integer $CountPerpanjang
 *
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class PeraturanPeminjamanHari extends \yii\db\ActiveRecord
{
    public $collectionCategory;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'peraturan_peminjaman_hari';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DayIndex', 'CreateBy', 'UpdateBy', 'MaxPinjamKoleksi', 'MaxLoanDays', 'DendaTenorMultiply', 'WarningLoanDueDay', 'DaySuspend', 'SuspendTenorMultiply', 'DayPerpanjang', 'CountPerpanjang'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['DendaTenorJumlah', 'DendaPerTenor', 'SuspendTenorJumlah'], 'number'],
            [['SuspendMember'], 'boolean'],
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
            'ID' => Yii::t('app', 'ID'),
            'DayIndex' => Yii::t('app', 'Day Index'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
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
