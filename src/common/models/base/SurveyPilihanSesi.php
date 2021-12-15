<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "survey_pilihan_sesi".
 *
 * @property integer $ID
 * @property integer $Survey_Pilihan_id
 * @property string $MemberNo
 * @property string $Sesi
 *
 * @property \common\models\SurveyPilihan $surveyPilihan
 */
class SurveyPilihanSesi extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_pilihan_sesi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Survey_Pilihan_id'], 'integer'],
            [['MemberNo'], 'string', 'max' => 50],
            [['Sesi'], 'string', 'max' => 255],
            [['Survey_Pilihan_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyPilihan::className(), 'targetAttribute' => ['Survey_Pilihan_id' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Survey_Pilihan_id' => Yii::t('app', 'Survey  Pilihan ID'),
            'MemberNo' => Yii::t('app', 'Member No'),
            'Sesi' => Yii::t('app', 'Sesi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyPilihan()
    {
        return $this->hasOne(\common\models\SurveyPilihan::className(), ['ID' => 'Survey_Pilihan_id']);
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
