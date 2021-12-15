<?php

namespace common\models;

use Yii;
use \common\models\base\JenisKelamin as BaseJenisKelamin;

/**
 * This is the model class for table "jenis_kelamin".
 */
class JenisKelamin extends BaseJenisKelamin
{
	public function attributeLabels()
    {
        return [
			'ID' => Yii::t('app', 'ID'),
            'Name' => Yii::t('app', 'Name'),
        ];
    }
}
