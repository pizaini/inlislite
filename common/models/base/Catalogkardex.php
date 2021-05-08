<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "catalogkardex".
 *
 * @property double $ID
 * @property double $Catalog_id
 * @property string $TanggalTerima
 * @property string $NomorKardex
 * @property string $CreateDate
 * @property string $CreateBy
 * @property string $CreateTerminal
 * @property string $UpdateDate
 * @property string $UpdateBy
 * @property string $UpdateTerminal
 *
 * @property \common\models\Catalogs $catalog
 */
class Catalogkardex extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalogkardex';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Catalog_id'], 'required'],
            [['Catalog_id'], 'number'],
            [['TanggalTerima', 'CreateDate', 'UpdateDate'], 'safe'],
            [['NomorKardex'], 'string', 'max' => 45],
            [['CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Catalog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['Catalog_id' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Catalog_id' => Yii::t('app', 'Catalog ID'),
            'TanggalTerima' => Yii::t('app', 'Tanggal Terima'),
            'NomorKardex' => Yii::t('app', 'Nomor Kardex'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(\common\models\Catalogs::className(), ['ID' => 'Catalog_id']);
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
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
