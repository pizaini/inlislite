<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "location_library_default".
 *
 * @property integer $ID
 * @property integer $Location_Library_id
 * @property integer $JenisAnggota_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTeminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Users $createBy
 * @property \common\models\JenisAnggota $jenisAnggota
 * @property \common\models\LocationLibrary $locationLibrary
 * @property \common\models\Users $updateBy
 */
class LocationLibraryDefault extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location_library_default';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Location_Library_id', 'JenisAnggota_id', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['CreateTeminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['JenisAnggota_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisAnggota::className(), 'targetAttribute' => ['JenisAnggota_id' => 'id']],
            [['Location_Library_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationLibrary::className(), 'targetAttribute' => ['Location_Library_id' => 'ID']],
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
            'Location_Library_id' => Yii::t('app', 'Location  Library ID'),
            'JenisAnggota_id' => Yii::t('app', 'Jenis Anggota ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTeminal' => Yii::t('app', 'Create Teminal'),
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
    public function getJenisAnggota()
    {
        return $this->hasOne(\common\models\JenisAnggota::className(), ['id' => 'JenisAnggota_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationLibrary()
    {
        return $this->hasOne(\common\models\LocationLibrary::className(), ['ID' => 'Location_Library_id']);
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
                'createdTerminalAttribute' => 'CreateTeminal',
                'updatedTerminalAttribute' => 'UpdateTerminal',
                'value' => \Yii::$app->request->userIP,
            ],
        ];
    }


    
}
