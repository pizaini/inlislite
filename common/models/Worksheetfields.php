<?php

namespace common\models;

use Yii;
use \common\models\base\Worksheetfields as BaseWorksheetfields;

/**
 * This is the model class for table "worksheetfields".
 */
class Worksheetfields extends BaseWorksheetfields
{
	public $Tag;
    public $Format_id;
    public $IsSerial;
    public $TagCode;
    public $TagName;
    public $Isi;
	/**
     * @inheritdoc
     * custom for lembar kerja akuisisi
     */
    public function rules()
    {
        return [
           [['Tag'], 'string', 'max' => 3],
           [['Field_id'], 'string', 'max' => 100],
           [['Format_id','Worksheet_id'], 'integer'],
           [['IsSerial','IsAkuisisi'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Tag' => Yii::t('app', 'Tag'),
            'Field_id' => Yii::t('app', 'Nama'),
            'IsAkuisisi' => Yii::t('app', 'Akuisisi'),
            'Format_id' => Yii::t('app', 'Format'),
            'IsSerial' => Yii::t('app', 'Serial'),
            'TagCode' => Yii::t('app', 'Tag'),
            'TagName' => Yii::t('app', 'Nama'),
        ];
    }

    public static function getStatusTag008($refferenceid,$worksheetid,$fieldid) {
        (int)$output = BaseWorksheetfields::find()
        ->leftJoin('worksheetfielditems',' worksheetfielditems.WorksheetField_id = worksheetfields.ID')
        ->where([
            'worksheetfielditems.Refference_id'=>$refferenceid,
            'worksheetfields.Worksheet_id'=>$worksheetid,
            'worksheetfields.Field_id'=>$fieldid
            ])
        ->count();
        return $output;
    }
}
