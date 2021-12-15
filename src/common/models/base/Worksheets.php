<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "worksheets".
 *
 * @property integer $ID
 * @property string $Name
 * @property integer $Format_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property integer $NoUrut
 * @property string $DEPOSITFORMAT_CODE
 * @property boolean $ISSERIAL
 * @property boolean $ISMUSIK
 * @property string $CODE
 * @property string $Keterangan
 * @property integer $MaxPinjamKoleksi
 * @property integer $MaxLoanDays
 * @property string $DendaType
 * @property string $DendaTenorJumlah
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
 * @property \common\models\AuthHeader[] $authHeaders
 * @property \common\models\Catalogs[] $catalogs
 * @property \common\models\Collectionmedias[] $collectionmedias
 * @property \common\models\Worksheetfields[] $worksheetfields
 * @property \common\models\Formats $format
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class Worksheets extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'worksheets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Format_id', 'CODE'], 'required'],
            [['Format_id', 'CreateBy', 'UpdateBy', 'NoUrut', 'MaxPinjamKoleksi', 'MaxLoanDays', 'DendaTenorMultiply', 'WarningLoanDueDay', 'DaySuspend', 'SuspendTenorMultiply', 'DayPerpanjang', 'CountPerpanjang'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['ISSERIAL', 'ISMUSIK', 'ISKARTOGRAFI', 'IsBerisiArtikel', 'SuspendMember'], 'boolean'],
            [['DendaTenorJumlah', 'DendaPerTenor', 'SuspendTenorJumlah'], 'number'],
            [['Name', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['DEPOSITFORMAT_CODE'], 'string', 'max' => 5],
            [['CODE'], 'string', 'max' => 10],
            [['Keterangan'], 'string', 'max' => 255],
            [['DendaType', 'DendaTenorSatuan', 'SuspendType', 'SuspendTenorSatuan'], 'string', 'max' => 45],
            [['Name'], 'unique'],
            [['Format_id'], 'exist', 'skipOnError' => true, 'targetClass' => Formats::className(), 'targetAttribute' => ['Format_id' => 'ID']],
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
            'Name' => Yii::t('app', 'Name'),
            'Format_id' => Yii::t('app', 'Format ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'NoUrut' => Yii::t('app', 'No Urut'),
            'DEPOSITFORMAT_CODE' => Yii::t('app', 'Depositformat  Code'),
            'ISSERIAL' => Yii::t('app', 'Isserial'),
            'ISKARTOGRAFI' => Yii::t('app', 'Iskartografi'),
            'ISMUSIK' => Yii::t('app', 'Ismusik'),
            'IsBerisiArtikel' => Yii::t('app', 'IsBerisiArtikel'),
            'CODE' => Yii::t('app', 'Code'),
            'Keterangan' => Yii::t('app', 'Keterangan'),
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
    public function getAuthHeaders()
    {
        return $this->hasMany(\common\models\AuthHeader::className(), ['Worksheet_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogs()
    {
        return $this->hasMany(\common\models\Catalogs::className(), ['Worksheet_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionmedias()
    {
        return $this->hasMany(\common\models\Collectionmedias::className(), ['Worksheet_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheetfields()
    {
        return $this->hasMany(\common\models\Worksheetfields::className(), ['Worksheet_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormat()
    {
        return $this->hasOne(\common\models\Formats::className(), ['ID' => 'Format_id']);
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
