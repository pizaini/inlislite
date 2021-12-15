<?php

namespace common\models;

use Yii;
use \common\models\base\Currency as BaseCurrency;

/**
 * This is the model class for table "currency".
 */
class Currency extends BaseCurrency
{
	public $Copies;

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Currency' => Yii::t('app', 'Currency'),
            'Description' => Yii::t('app', 'Description'),
            'Sort_ID' => Yii::t('app', 'Sort  ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'Copies' => Yii::t('app', 'Copies'),
        ];
    }
}
