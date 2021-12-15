<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "locations".
 *
 * @property integer $ID
 * @property string $Code
 * @property string $Name
 * @property string $Description
 * @property boolean $ISPUSTELING
 * @property string $UrlLogo
 * @property boolean $IsPrintBarcode
 * @property boolean $IsGenerateVisitorNumber
 * @property boolean $IsInformationSought
 * @property boolean $IsVisitsDestination
 * @property integer $LocationLibrary_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property string $KIILastUploadDate
 *
 * @property \common\models\Bacaditempat[] $bacaditempats
 * @property \common\models\CheckpointLocations[] $checkpointLocations
 * @property \common\models\Collections[] $collections
 * @property \common\models\Groupguesses[] $groupguesses
 * @property \common\models\Users $createBy
 * @property \common\models\LocationLibrary $locationLibrary
 * @property \common\models\Users $updateBy
 * @property \common\models\MasterLoker[] $masterLokers
 * @property \common\models\Memberguesses[] $memberguesses
 * @property \common\models\Readinlocation[] $readinlocations
 * @property \common\models\Stockopnamedetail[] $stockopnamedetails
 * @property \common\models\Stockopnamedetail[] $stockopnamedetails0
 */
class Locations extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'locations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'Name'], 'required'],
            [['ISPUSTELING', 'IsPrintBarcode', 'IsGenerateVisitorNumber', 'IsInformationSought', 'IsVisitsDestination'], 'boolean'],
            [['LocationLibrary_id', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate', 'KIILastUploadDate'], 'safe'],
            [['Code'], 'string', 'max' => 20],
            [['Name', 'Description'], 'string', 'max' => 255],
            [['UrlLogo', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['LocationLibrary_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationLibrary::className(), 'targetAttribute' => ['LocationLibrary_id' => 'ID']],
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
            'Code' => Yii::t('app', 'Code'),
            'Name' => Yii::t('app', 'Name'),
            'Description' => Yii::t('app', 'Description'),
            'ISPUSTELING' => Yii::t('app', 'Ispusteling'),
            'UrlLogo' => Yii::t('app', 'Url Logo'),
            'IsPrintBarcode' => Yii::t('app', 'Is Print Barcode'),
            'IsGenerateVisitorNumber' => Yii::t('app', 'Is Generate Visitor Number'),
            'IsInformationSought' => Yii::t('app', 'Is Information Sought'),
            'IsVisitsDestination' => Yii::t('app', 'Is Visits Destination'),
            'LocationLibrary_id' => Yii::t('app', 'Location Library ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'KIILastUploadDate' => Yii::t('app', 'Kiilast Upload Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBacaditempats()
    {
        return $this->hasMany(\common\models\Bacaditempat::className(), ['Location_Id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckpointLocations()
    {
        return $this->hasMany(\common\models\CheckpointLocations::className(), ['Location_ID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections()
    {
        return $this->hasMany(\common\models\Collections::className(), ['Location_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupguesses()
    {
        return $this->hasMany(\common\models\Groupguesses::className(), ['Location_ID' => 'ID']);
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
    public function getLocationLibrary()
    {
        return $this->hasOne(\common\models\LocationLibrary::className(), ['ID' => 'LocationLibrary_id']);
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
    public function getMasterLokers()
    {
        return $this->hasMany(\common\models\MasterLoker::className(), ['locations_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberguesses()
    {
        return $this->hasMany(\common\models\Memberguesses::className(), ['Location_Id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReadinlocations()
    {
        return $this->hasMany(\common\models\Readinlocation::className(), ['LocationId' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnamedetails()
    {
        return $this->hasMany(\common\models\Stockopnamedetail::className(), ['PrevLocationID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnamedetails0()
    {
        return $this->hasMany(\common\models\Stockopnamedetail::className(), ['CurrentLocationID' => 'ID']);
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
