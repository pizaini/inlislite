<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "stockopnamedetail".
 *
 * @property double $ID
 * @property integer $StockOpnameID
 * @property double $CollectionID
 * @property integer $PrevLocationID
 * @property integer $CurrentLocationID
 * @property integer $PrevStatusID
 * @property integer $CurrentStatusID
 * @property integer $PrevCollectionRuleID
 * @property integer $CurrentCollectionRuleID
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Collections $collection
 * @property \common\models\Collectionrules $prevCollectionRule
 * @property \common\models\Collectionrules $currentCollectionRule
 * @property \common\models\Collectionstatus $prevStatus
 * @property \common\models\Collectionstatus $currentStatus
 * @property \common\models\Users $createBy
 * @property \common\models\Locations $prevLocation
 * @property \common\models\Locations $currentLocation
 * @property \common\models\Stockopname $stockOpname
 * @property \common\models\Users $updateBy
 */
class Stockopnamedetail extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stockopnamedetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['StockOpnameID', 'CollectionID'], 'required'],
            [['StockOpnameID', 'PrevLocationID', 'CurrentLocationID', 'PrevStatusID', 'CurrentStatusID', 'PrevCollectionRuleID', 'CurrentCollectionRuleID', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CollectionID'], 'number'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CollectionID'], 'exist', 'skipOnError' => true, 'targetClass' => Collections::className(), 'targetAttribute' => ['CollectionID' => 'ID']],
            [['PrevCollectionRuleID'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionrules::className(), 'targetAttribute' => ['PrevCollectionRuleID' => 'ID']],
            [['CurrentCollectionRuleID'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionrules::className(), 'targetAttribute' => ['CurrentCollectionRuleID' => 'ID']],
            [['PrevStatusID'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionstatus::className(), 'targetAttribute' => ['PrevStatusID' => 'ID']],
            [['CurrentStatusID'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionstatus::className(), 'targetAttribute' => ['CurrentStatusID' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['PrevLocationID'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['PrevLocationID' => 'ID']],
            [['CurrentLocationID'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['CurrentLocationID' => 'ID']],
            [['StockOpnameID'], 'exist', 'skipOnError' => true, 'targetClass' => Stockopname::className(), 'targetAttribute' => ['StockOpnameID' => 'ID']],
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
            'StockOpnameID' => Yii::t('app', 'Stock Opname ID'),
            'CollectionID' => Yii::t('app', 'Collection ID'),
            'PrevLocationID' => Yii::t('app', 'Prev Location ID'),
            'CurrentLocationID' => Yii::t('app', 'Current Location ID'),
            'PrevStatusID' => Yii::t('app', 'Prev Status ID'),
            'CurrentStatusID' => Yii::t('app', 'Current Status ID'),
            'PrevCollectionRuleID' => Yii::t('app', 'Prev Collection Rule ID'),
            'CurrentCollectionRuleID' => Yii::t('app', 'Current Collection Rule ID'),
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
    public function getCollection()
    {
        return $this->hasOne(\common\models\Collections::className(), ['ID' => 'CollectionID']);
    }
        public function getLocation_id()
        {
            return $this->hasOne(\common\models\Collections::className(), ['Location_id' => 'CurrentLocationID']);
        }
        public function getStatus_id()
        {
            return $this->hasOne(\common\models\Collections::className(), ['Status_id' => 'CurrentStatusID']);
        }
        public function getRule_id()
        {
            return $this->hasOne(\common\models\Collections::className(), ['Rule_id' => 'CurrentCollectionRuleID']);
        }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrevCollectionRule()
    {
        return $this->hasOne(\common\models\Collectionrules::className(), ['ID' => 'PrevCollectionRuleID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentCollectionRule()
    {
        return $this->hasOne(\common\models\Collectionrules::className(), ['ID' => 'CurrentCollectionRuleID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrevStatus()
    {
        return $this->hasOne(\common\models\Collectionstatus::className(), ['ID' => 'PrevStatusID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentStatus()
    {
        return $this->hasOne(\common\models\Collectionstatus::className(), ['ID' => 'CurrentStatusID']);
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
    public function getPrevLocation()
    {
        return $this->hasOne(\common\models\Locations::className(), ['ID' => 'PrevLocationID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentLocation()
    {
        return $this->hasOne(\common\models\Locations::className(), ['ID' => 'CurrentLocationID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOpname()
    {
        return $this->hasOne(\common\models\Stockopname::className(), ['ID' => 'StockOpnameID']);
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
