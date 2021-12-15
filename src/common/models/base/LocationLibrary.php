<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "location_library".
 *
 * @property integer $ID
 * @property string $Code
 * @property string $Name
 * @property string $Address
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property string $KIILastUploadDate
 *
 * @property \common\models\Collections[] $collections
 * @property \common\models\Groupguesses[] $groupguesses
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 * @property \common\models\LocationLibraryDefault[] $locationLibraryDefaults
 * @property \common\models\Locations[] $locations
 * @property \common\models\Memberguesses[] $memberguesses
 * @property \common\models\Memberloanauthorizelocation[] $memberloanauthorizelocations
 * @property \common\models\Membersonline[] $membersonlines
 * @property \common\models\Userloclibforcol[] $userloclibforcols
 * @property \common\models\Userloclibforloan[] $userloclibforloans
 */
class LocationLibrary extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location_library';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'Name'], 'required'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate', 'KIILastUploadDate'], 'safe'],
            [['Code'], 'string', 'max' => 50],
            [['Name', 'Address'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
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
            'Code' => Yii::t('app', 'Code'),
            'Name' => Yii::t('app', 'Name'),
            'Address' => Yii::t('app', 'Address'),
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
    public function getCollections()
    {
        return $this->hasMany(\common\models\Collections::className(), ['Location_Library_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupguesses()
    {
        return $this->hasMany(\common\models\Groupguesses::className(), ['LocationLoans_ID' => 'ID']);
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
    public function getLocationLibraryDefaults()
    {
        return $this->hasMany(\common\models\LocationLibraryDefault::className(), ['Location_Library_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(\common\models\Locations::className(), ['LocationLibrary_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberguesses()
    {
        return $this->hasMany(\common\models\Memberguesses::className(), ['LOCATIONLOANS_ID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberloanauthorizelocations()
    {
        return $this->hasMany(\common\models\Memberloanauthorizelocation::className(), ['LocationLoan_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersonlines()
    {
        return $this->hasMany(\common\models\Membersonline::className(), ['LocationLoanId' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserloclibforcols()
    {
        return $this->hasMany(\common\models\Userloclibforcol::className(), ['LocLib_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserloclibforloans()
    {
        return $this->hasMany(\common\models\Userloclibforloan::className(), ['LocLib_id' => 'ID']);
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
