<?php

namespace common\models;

use Yii;
use \common\models\base\Settingcatalogdetail as BaseSettingcatalogdetail;

/**
 * This is the model class for table "settingcatalogdetail".
 */
class Settingcatalogdetail extends BaseSettingcatalogdetail
{

	public $TagInp;
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Field_id' => Yii::t('app', 'Field ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'TagInp' => Yii::t('app', 'Tag'),
        ];
    }
}
