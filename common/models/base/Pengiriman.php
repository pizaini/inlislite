<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "pengiriman".
 *
 * @property integer $ID
 * @property string $JudulKiriman
 * @property string $PenanggungJawab
 * @property string $NipPenanggungJawab
 * @property string $FromDate
 * @property string $ToDate
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 * @property \common\models\PengirimanKoleksi[] $pengirimanKoleksis
 */
class Pengiriman extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pengiriman';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['JudulKiriman', 'PenanggungJawab'], 'required'],
            [['JudulKiriman'], 'string'],
            [['FromDate', 'ToDate', 'CreateDate', 'UpdateDate'], 'safe'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['PenanggungJawab', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['NipPenanggungJawab'], 'string', 'max' => 50],
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
            'JudulKiriman' => Yii::t('app', 'Judul Kiriman'),
            'PenanggungJawab' => Yii::t('app', 'Penanggung Jawab'),
            'NipPenanggungJawab' => Yii::t('app', 'Nip Penanggung Jawab'),
            'FromDate' => Yii::t('app', 'From Date'),
            'ToDate' => Yii::t('app', 'To Date'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getPengirimanKoleksis()
    {
        return $this->hasMany(\common\models\PengirimanKoleksi::className(), ['PengirimanID' => 'ID']);
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
             \nhkey\arh\ActiveRecordHistoryBehavior::className(),
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
