<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "colloclib".
 *
 * @property double $Col_id
 * @property integer $LocLib_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Collections $col
 * @property \common\models\LocationLibrary $locLib
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class Colloclib extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'colloclib';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Col_id', 'LocLib_id'], 'required'],
            [['Col_id'], 'number'],
            [['LocLib_id', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Col_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collections::className(), 'targetAttribute' => ['Col_id' => 'ID']],
            [['LocLib_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationLibrary::className(), 'targetAttribute' => ['LocLib_id' => 'ID']],
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
            'Col_id' => Yii::t('app', 'Col ID'),
            'LocLib_id' => Yii::t('app', 'Loc Lib ID'),
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
    public function getCol()
    {
        return $this->hasOne(\common\models\Collections::className(), ['ID' => 'Col_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocLib()
    {
        return $this->hasOne(\common\models\LocationLibrary::className(), ['ID' => 'LocLib_id']);
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
