<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "akuisisi_action".
 *
 * @property integer $ID
 * @property integer $AkuisisiID
 * @property string $OldStatus
 * @property string $NewStatus
 * @property string $Action
 * @property string $Comment
 * @property string $ActionBy
 * @property string $ActionDate
 *
 * @property \common\models\Akuisisi $akuisisi
 */
class AkuisisiAction extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'akuisisi_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AkuisisiID', 'OldStatus', 'NewStatus', 'Action', 'Comment', 'ActionBy', 'ActionDate'], 'required'],
            [['AkuisisiID'], 'integer'],
            [['ActionDate'], 'safe'],
            [['OldStatus', 'NewStatus', 'Action', 'ActionBy'], 'string', 'max' => 50],
            [['Comment'], 'string', 'max' => 1000],
            [['AkuisisiID'], 'exist', 'skipOnError' => true, 'targetClass' => Akuisisi::className(), 'targetAttribute' => ['AkuisisiID' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'AkuisisiID' => Yii::t('app', 'Akuisisi ID'),
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
    public function getAkuisisi()
    {
        return $this->hasOne(\common\models\Akuisisi::className(), ['ID' => 'AkuisisiID']);
    }


    
}
