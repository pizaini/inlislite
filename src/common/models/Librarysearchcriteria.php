<?php

namespace common\models;

use Yii;
use \common\models\base\Librarysearchcriteria as BaseLibrarysearchcriteria;

/**
 * This is the model class for table "librarysearchcriteria".
 */
class Librarysearchcriteria extends BaseLibrarysearchcriteria
{
    //public $multi;
	public static function loadCriteriaByLibrary($libraryid) {
        $model= BaseLibrarysearchcriteria::find()
        ->addSelect(['ID','CRITERIANAME'])
        ->where(['LIBRARYID'=>$libraryid])
        ->all();
        return $model;
    }
}
