<?php

namespace common\models;

use Yii;
use \common\models\base\MasterProgramStudi as BaseMasterProgramStudi;

/**
 * This is the model class for table "master_program_studi".
 */
class MasterProgramStudi extends BaseMasterProgramStudi
{
  public $jurusan;

   public static function getOptionsByJurusan($id_jurusan) {
        $data = static::find()->where(['id_jurusan'=>$id_jurusan])->select(['id','Nama as name'])->asArray()->all();
        $value = (count($data) == 0) ? ['' => ''] : $data;

        return $value;
    }
}
