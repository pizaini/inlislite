<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "akuisisi_worksheet".
 *
 * @property integer $ID
 * @property string $Name
 * @property integer $Main_Worksheet_ID
 *
 * @property \common\models\Akuisisi[] $akuisisis
 * @property \common\models\AkuisisiLog[] $akuisisiLogs
 * @property \common\models\AkuisisiMap $akuisisiMap
 * @property \common\models\AkuisisiRaw[] $akuisisiRaws
 * @property \common\models\AkuisisiRawLog[] $akuisisiRawLogs
 * @property \common\models\Worksheets $mainWorksheet
 */
class AkuisisiWorksheet extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'akuisisi_worksheet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Main_Worksheet_ID'], 'integer'],
            [['Name'], 'string', 'max' => 50],
            [['Main_Worksheet_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Worksheets::className(), 'targetAttribute' => ['Main_Worksheet_ID' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Name' => Yii::t('app', 'Name'),
            'Main_Worksheet_ID' => Yii::t('app', 'Main  Worksheet  ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisis()
    {
        return $this->hasMany(\common\models\Akuisisi::className(), ['WorksheetID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisiLogs()
    {
        return $this->hasMany(\common\models\AkuisisiLog::className(), ['WorksheetID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisiMap()
    {
        return $this->hasOne(\common\models\AkuisisiMap::className(), ['WorksheetID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisiRaws()
    {
        return $this->hasMany(\common\models\AkuisisiRaw::className(), ['WorksheetID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkuisisiRawLogs()
    {
        return $this->hasMany(\common\models\AkuisisiRawLog::className(), ['WorksheetID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainWorksheet()
    {
        return $this->hasOne(\common\models\Worksheets::className(), ['ID' => 'Main_Worksheet_ID']);
    }


    
}
