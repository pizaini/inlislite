<?php

namespace common\models;

use Yii;
use \common\models\base\Library as BaseLibrary;

/**
 * This is the model class for table "library".
 */
class Library extends BaseLibrary
{
	 /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'NAME' => Yii::t('app', 'Singkatan'),
            'URL' => Yii::t('app', 'Link'),
            'PORT' => Yii::t('app', 'Port'),
            'DATABASENAME' => Yii::t('app', 'Nama Database'),
            'RECORDSYNTAX' => Yii::t('app', 'sintaks'),
            'FULLNAME' => Yii::t('app', 'Nama'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
        ];
    }

    public static function loadSumber() {
        $model= BaseLibrary::find()
        ->addSelect(['ID','CONCAT_WS(" - ",NAME,FULLNAME) AS NAME'])
        ->all();
        return $model;
    }
}
