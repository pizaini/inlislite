<?php

namespace common\models;

use Yii;
use \common\models\base\Partners as BasePartners;

/**
 * This is the model class for table "partners".
 */
class Partners extends BasePartners
{
	public $Copies;

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Name' => Yii::t('app', 'Name'),
            'Address' => Yii::t('app', 'Address'),
            'Phone' => Yii::t('app', 'Phone'),
            'Fax' => Yii::t('app', 'Fax'),
            /*'Partnership' => Yii::t('app', 'Partnership'),*/
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'Copies' => Yii::t('app', 'Copies'),
        ];
    }

    public static function getOrderAlphabhet()
    {
        $model  = Partners::findBySql("SELECT `partners`.* FROM `partners` LEFT JOIN (SELECT `Partner_id`, COUNT(ID) AS Copies FROM `collections`) `collectionCount` ON  collectionCount.Partner_id = partners.id WHERE TRIM(`partners`.`Name`) != '' ORDER BY IF(TRIM(`Name`) = '' OR TRIM(`Name`) IS NULL,1,0),REPLACE(TRIM(`Name`),'(','') ")->all();
        return $model;
    }
}
