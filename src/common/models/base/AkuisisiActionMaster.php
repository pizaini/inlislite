<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "akuisisi_action_master".
 *
 * @property string $CurrentStatus
 * @property string $Action
 * @property string $NewStatus
 * @property string $ActionClass
 * @property integer $SortId
 */
class AkuisisiActionMaster extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'akuisisi_action_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CurrentStatus', 'Action', 'NewStatus'], 'required'],
            [['SortId'], 'integer'],
            [['CurrentStatus', 'Action', 'NewStatus', 'ActionClass'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CurrentStatus' => Yii::t('app', 'Current Status'),
            'Action' => Yii::t('app', 'Action'),
            'NewStatus' => Yii::t('app', 'New Status'),
            'ActionClass' => Yii::t('app', 'Action Class'),
            'SortId' => Yii::t('app', 'Sort ID'),
        ];
    }


    
}
