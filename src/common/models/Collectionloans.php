<?php

namespace common\models;

use Yii;
use \common\models\base\Collectionloans as BaseCollectionloans;

/**
 * This is the model class for table "collectionloans".
 */
class Collectionloans extends BaseCollectionloans
{
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationLibrary()
    {
        return $this->hasOne(\common\models\locationlibrary::className(), ['ID' => 'LocationLibrary_id']);
    }
}
