<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "survey_pilihan".
 *
 * @property integer $ID
 * @property integer $Survey_Pertanyaan_id
 * @property string $Pilihan
 * @property integer $ChoosenCount
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Users $createBy
 * @property \common\models\SurveyPertanyaan $surveyPertanyaan
 * @property \common\models\Users $updateBy
 * @property \common\models\SurveyPilihanSesi[] $surveyPilihanSesis
 */
class SurveyPilihan extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_pilihan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Survey_Pertanyaan_id', 'ChoosenCount', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Pilihan'], 'required'],
            [['Pilihan'], 'string'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['Survey_Pertanyaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyPertanyaan::className(), 'targetAttribute' => ['Survey_Pertanyaan_id' => 'ID']],
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
            'Survey_Pertanyaan_id' => Yii::t('app', 'Survey  Pertanyaan ID'),
            'Pilihan' => Yii::t('app', 'Pilihan'),
            'ChoosenCount' => Yii::t('app', 'Choosen Count'),
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
    public function getCreateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'CreateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyPertanyaan()
    {
        return $this->hasOne(\common\models\SurveyPertanyaan::className(), ['ID' => 'Survey_Pertanyaan_id']);
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
    public function getSurveyPilihanSesis()
    {
        return $this->hasMany(\common\models\SurveyPilihanSesi::className(), ['Survey_Pilihan_id' => 'ID']);
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
