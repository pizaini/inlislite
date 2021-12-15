<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "master_loker".
 *
 * @property integer $ID
 * @property string $No
 * @property string $Name
 * @property integer $locations_id
 * @property string $status
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Lockers[] $lockers
 * @property \common\models\Users $createBy
 * @property \common\models\Locations $locations
 * @property \common\models\Users $updateBy
 */
class MasterLoker extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'master_loker';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['No', 'Name'], 'required'],
            [['locations_id', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['No', 'Name'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 20],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['locations_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['locations_id' => 'ID']],
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
            'No' => Yii::t('app', 'No'),
            'Name' => Yii::t('app', 'Name'),
            'locations_id' => Yii::t('app', 'Locations ID'),
            'status' => Yii::t('app', 'Status'),
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
    public function getLockers()
    {
        return $this->hasMany(\common\models\Lockers::className(), ['loker_id' => 'ID']);
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
    public function getLocations()
    {
        return $this->hasOne(\common\models\Locations::className(), ['ID' => 'locations_id']);
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
