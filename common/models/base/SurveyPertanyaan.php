<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "survey_pertanyaan".
 *
 * @property integer $ID
 * @property integer $Survey_id
 * @property string $Pertanyaan
 * @property string $JenisPertanyaan
 * @property string $Orientation
 * @property boolean $IsMandatory
 * @property boolean $IsCanMultipleAnswer
 * @property integer $NoUrut
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\SurveyIsian[] $surveyIsians
 * @property \common\models\Users $createBy
 * @property \common\models\Survey $survey
 * @property \common\models\Users $updateBy
 * @property \common\models\SurveyPilihan[] $surveyPilihans
 */
class SurveyPertanyaan extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_pertanyaan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Survey_id', 'NoUrut', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Pertanyaan'], 'required'],
            [['Pertanyaan'], 'string'],
            [['IsMandatory', 'IsCanMultipleAnswer'], 'boolean'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['JenisPertanyaan', 'Orientation'], 'string', 'max' => 20],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['Survey_id'], 'exist', 'skipOnError' => true, 'targetClass' => Survey::className(), 'targetAttribute' => ['Survey_id' => 'ID']],
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
            'Survey_id' => Yii::t('app', 'Survey ID'),
            'Pertanyaan' => Yii::t('app', 'Pertanyaan'),
            'JenisPertanyaan' => Yii::t('app', 'Jenis Pertanyaan'),
            'Orientation' => Yii::t('app', 'Orientation'),
            'IsMandatory' => Yii::t('app', 'Is Mandatory'),
            'IsCanMultipleAnswer' => Yii::t('app', 'Is Can Multiple Answer'),
            'NoUrut' => Yii::t('app', 'No Urut'),
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
    public function getSurveyIsians()
    {
        return $this->hasMany(\common\models\SurveyIsian::className(), ['Survey_Pertanyaan_id' => 'ID']);
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
    public function getSurvey()
    {
        return $this->hasOne(\common\models\Survey::className(), ['ID' => 'Survey_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyPilihans()
    {
        return $this->hasMany(\common\models\SurveyPilihan::className(), ['Survey_Pertanyaan_id' => 'ID']);
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
