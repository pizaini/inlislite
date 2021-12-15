<?php

namespace common\models;

use Yii;
use \common\models\base\Warnaddc as BaseWarnaddc;

/**
 * This is the model class for table "warnaddc".
 */
class Warnaddc extends BaseWarnaddc
{
	public $Copies;
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'KodeDDC' => Yii::t('app', 'Kode Ddc'),
            'Warna' => Yii::t('app', 'Warna'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'Copies' => Yii::t('app','Copies'),
        ];
    }
}
