<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "auth_catalog".
 *
 * @property integer $ID
 * @property integer $AUTH_HEADER_ID
 * @property double $CATALOG_ID
 * @property string $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property string $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\AuthHeader $aUTHHEADER
 * @property \common\models\Catalogs $cATALOG
 */
class AuthCatalog extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AUTH_HEADER_ID', 'CATALOG_ID'], 'required'],
            [['AUTH_HEADER_ID'], 'integer'],
            [['CATALOG_ID'], 'number'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            [['AUTH_HEADER_ID'], 'exist', 'skipOnError' => true, 'targetClass' => AuthHeader::className(), 'targetAttribute' => ['AUTH_HEADER_ID' => 'ID']],
            [['CATALOG_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['CATALOG_ID' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'AUTH_HEADER_ID' => Yii::t('app', 'Auth  Header  ID'),
            'CATALOG_ID' => Yii::t('app', 'Catalog  ID'),
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
    public function getAUTHHEADER()
    {
        return $this->hasOne(\common\models\AuthHeader::className(), ['ID' => 'AUTH_HEADER_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCATALOG()
    {
        return $this->hasOne(\common\models\Catalogs::className(), ['ID' => 'CATALOG_ID']);
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
