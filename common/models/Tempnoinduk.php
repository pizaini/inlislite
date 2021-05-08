<?php

namespace common\models;

use Yii;
use \common\models\base\Tempnoinduk as BaseTempnoinduk;

/**
 * This is the model class for table "tempnoinduk".
 */
class Tempnoinduk extends BaseTempnoinduk
{

	public function saveOnce($noinduk) {
        $model = new Tempnoinduk;
        $model->NoInduk=$noinduk;
        $model->save();
    }
}
