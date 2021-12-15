<?php

namespace common\models;

use Yii;
use \common\models\base\Registrasi as BaseRegistrasi;

/**
 * This is the model class for table "registrasi".
 */
class Registrasi extends BaseRegistrasi
{
    public static function findByActivationCode($code)
    {
        return static::findOne(['ActivationCode' => $code]);
    }
}
