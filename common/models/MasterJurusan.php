<?php

namespace common\models;

use Yii;
use \common\models\base\MasterJurusan as BaseMasterJurusan;

/**
 * This is the model class for table "master_jurusan".
 */
class MasterJurusan extends BaseMasterJurusan
{

    /**
     * @inheritdoc
     */
    

     public static function getOptionsByFakultas($id_fakultas) {
        $data = static::find()->where(['id_fakultas'=>$id_fakultas])->select(['id','Nama as name'])->asArray()->all();
        $value = (count($data) == 0) ? ['' => ''] : $data;

        return $value;
    }
}
