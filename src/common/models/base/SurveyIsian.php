<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "survey_isian".
 *
 * @property integer $ID
 * @property integer $Survey_Pertanyaan_id
 * @property string $Sesi
 * @property string $MemberNo
 * @property string $Isian
 *
 * @property \common\models\SurveyPertanyaan $surveyPertanyaan
 */
class SurveyIsian extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_isian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Survey_Pertanyaan_id'], 'integer'],
            [['Isian'], 'string'],
            [['Sesi'], 'string', 'max' => 255],
            [['MemberNo'], 'string', 'max' => 50],
            [['Survey_Pertanyaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyPertanyaan::className(), 'targetAttribute' => ['Survey_Pertanyaan_id' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Survey_Pertanyaan_id' => Yii::t('app', 'Survey  Pertanyaan ID'),
            'Sesi' => Yii::t('app', 'Sesi'),
            'MemberNo' => Yii::t('app', 'Member No'),
            'Isian' => Yii::t('app', 'Isian'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyPertanyaan()
    {
        return $this->hasOne(\common\models\SurveyPertanyaan::className(), ['ID' => 'Survey_Pertanyaan_id']);
    }


/**
     * @inheritdoc
     * @return type array
     */
    public function behaviors()
    {
        return [
        // \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
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
