<?php

namespace common\models;

use Yii;
use \common\models\base\Holidays as BaseHolidays;

/**
 * This is the model class for table "holidays".
 */
class Holidays extends BaseHolidays
{
	    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Dates', 'Names'], 'required'],
            [['Dates', 'CreateDate', 'UpdateDate'], 'safe'],
            ['Dates', 'unique'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['Names'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }
}
