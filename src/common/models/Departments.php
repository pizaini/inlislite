<?php

namespace common\models;

use Yii;
use \common\models\base\Departments as BaseDepartments;

/**
 * This is the model class for table "departments".
 */
class Departments extends BaseDepartments
{
	public function attributeLabels()
    {
        return [
			'ID' => Yii::t('app', 'ID'),
			'Code' => Yii::t('app', 'Code'),
            'Name' => Yii::t('app', 'Name'),
			'Description' => Yii::t('app', 'Description'),
			'IsActive' => Yii::t('app', 'IsActive'),
			'CreateTerminal' => Yii::t('app', 'CreateTerminal'),
			'UpdateTerminal' => Yii::t('app', 'UpdateTerminal'),
        ];
    }
}
