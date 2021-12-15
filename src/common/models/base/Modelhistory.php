<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "modelhistory".
 *
 * @property integer $id
 * @property string $date
 * @property string $table
 * @property string $field_name
 * @property string $field_id
 * @property string $old_value
 * @property string $new_value
 * @property integer $type
 * @property string $user_id
 */
class Modelhistory extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modelhistory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'table', 'field_name', 'field_id', 'type', 'user_id'], 'required'],
            [['date'], 'safe'],
            [['old_value', 'new_value'], 'string'],
            [['type'], 'integer'],
            [['table', 'field_name', 'field_id', 'user_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'Date'),
            'table' => Yii::t('app', 'Table'),
            'field_name' => Yii::t('app', 'Field Name'),
            'field_id' => Yii::t('app', 'Field ID'),
            'old_value' => Yii::t('app', 'Old Value'),
            'new_value' => Yii::t('app', 'New Value'),
            'type' => Yii::t('app', 'Type'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
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
