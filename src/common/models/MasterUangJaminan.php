<?php

namespace common\models;

use Yii;
use \common\models\base\MasterUangJaminan as BaseMasterUangJaminan;

/**
 * This is the model class for table "master_loker".
 */
class MasterUangJaminan extends BaseMasterUangJaminan
{


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'No' => Yii::t('app', 'Nominal'),
            'Name' => Yii::t('app', 'Terbilang'),

        ];
    }



}
