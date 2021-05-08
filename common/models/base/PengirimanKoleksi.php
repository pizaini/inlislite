<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "pengiriman_koleksi".
 *
 * @property integer $ID
 * @property double $Collection_id
 * @property string $BIBID
 * @property string $JUDUL
 * @property string $TAHUNTERBIT
 * @property string $CALLNUMBER
 * @property string $NOBARCODE
 * @property string $NOINDUK
 * @property integer $QUANTITY
 * @property string $TANGGALKIRIM
 * @property integer $PengirimanID
 * @property boolean $IsCheck
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Pengiriman $pengiriman
 * @property \common\models\Collections $collection
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class PengirimanKoleksi extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pengiriman_koleksi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Collection_id'], 'required'],
            [['Collection_id'], 'number'],
            [['QUANTITY', 'PengirimanID', 'CreateBy', 'UpdateBy'], 'integer'],
            [['TANGGALKIRIM', 'CreateDate', 'UpdateDate'], 'safe'],
            [['IsCheck'], 'boolean'],
            [['BIBID', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 50],
            [['JUDUL'], 'string', 'max' => 4000],
            [['TAHUNTERBIT'], 'string', 'max' => 20],
            [['CALLNUMBER', 'NOINDUK'], 'string', 'max' => 255],
            [['NOBARCODE'], 'string', 'max' => 100],
            [['PengirimanID'], 'exist', 'skipOnError' => true, 'targetClass' => Pengiriman::className(), 'targetAttribute' => ['PengirimanID' => 'ID']],
            [['Collection_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collections::className(), 'targetAttribute' => ['Collection_id' => 'ID']],
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
            'Collection_id' => Yii::t('app', 'Collection ID'),
            'BIBID' => Yii::t('app', 'Bibid'),
            'JUDUL' => Yii::t('app', 'Judul'),
            'TAHUNTERBIT' => Yii::t('app', 'Tahunterbit'),
            'CALLNUMBER' => Yii::t('app', 'Callnumber'),
            'NOBARCODE' => Yii::t('app', 'Nobarcode'),
            'NOINDUK' => Yii::t('app', 'Noinduk'),
            'QUANTITY' => Yii::t('app', 'Quantity'),
            'TANGGALKIRIM' => Yii::t('app', 'Tanggalkirim'),
            'PengirimanID' => Yii::t('app', 'Pengiriman ID'),
            'IsCheck' => Yii::t('app', 'Is Check'),
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
    public function getPengiriman()
    {
        return $this->hasOne(\common\models\Pengiriman::className(), ['ID' => 'PengirimanID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(\common\models\Collections::className(), ['ID' => 'Collection_id']);
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
