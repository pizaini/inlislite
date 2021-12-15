<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "zlog".
 *
 * @property integer $ID
 * @property string $WHO
 * @property string $LOG_DATE
 * @property string $TABLE_NAME
 * @property string $TABLE_ID
 * @property string $ACTION
 * @property string $OLD_VALUE
 * @property string $NEW_VALUE
 */
class Zlog extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zlog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['WHO', 'LOG_DATE', 'TABLE_NAME', 'TABLE_ID', 'ACTION'], 'required'],
            [['LOG_DATE'], 'safe'],
            [['WHO', 'TABLE_NAME', 'TABLE_ID', 'ACTION'], 'string', 'max' => 50],
            [['OLD_VALUE', 'NEW_VALUE'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'WHO' => Yii::t('app', 'Who'),
            'LOG_DATE' => Yii::t('app', 'Log  Date'),
            'TABLE_NAME' => Yii::t('app', 'Table  Name'),
            'TABLE_ID' => Yii::t('app', 'Table  ID'),
            'ACTION' => Yii::t('app', 'Action'),
            'OLD_VALUE' => Yii::t('app', 'Old  Value'),
            'NEW_VALUE' => Yii::t('app', 'New  Value'),
        ];
    }


    
}
