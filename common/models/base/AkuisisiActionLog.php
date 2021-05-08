<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "akuisisi_action_log".
 *
 * @property integer $ID
 * @property integer $AkuisisiLogID
 * @property string $OldStatus
 * @property string $NewStatus
 * @property string $Action
 * @property string $Comment
 * @property string $ActionBy
 * @property string $ActionDate
 *
 * @property \common\models\AkuisisiLog $akuisisiLog
 */
class AkuisisiActionLog extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'akuisisi_action_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'AkuisisiLogID', 'OldStatus', 'NewStatus', 'Action', 'Comment', 'ActionBy', 'ActionDate'], 'required'],
            [['ID', 'AkuisisiLogID'], 'integer'],
            [['ActionDate'], 'safe'],
            [['OldStatus', 'NewStatus', 'Action', 'ActionBy'], 'string', 'max' => 50],
            [['Comment'], 'string', 'max' => 1000],
            [['AkuisisiLogID'], 'exist', 'skipOnError' => true, 'targetClass' => AkuisisiLog::className(), 'targetAttribute' => ['AkuisisiLogID' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'AkuisisiLogID' => Yii::t('app', 'Akuisisi Log ID'),
            'OldStatus' => Yii::t('app', 'Old Status'),
            'NewStatus' => Yii::t('app', 'New Status'),
            'Action' => Yii::t('app', 'Action'),
            'Comment' => Yii::t('app', 'Comment'),
            'ActionBy' => Yii::t('app', 'Action By'),
            'ActionDate' => Yii::t('app', 'Action Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisiLog()
    {
        return $this->hasOne(\common\models\AkuisisiLog::className(), ['ID' => 'AkuisisiLogID']);
    }


    
}
