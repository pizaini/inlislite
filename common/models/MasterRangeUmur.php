<?php

namespace common\models;

use Yii;
use \common\models\base\MasterRangeUmur as BaseMasterRangeUmur;

/**
 * This is the model class for table "master_range_umur".
 */
class MasterRangeUmur extends BaseMasterRangeUmur
{
   /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'umur1' => Yii::t('app', 'Umur Batas Bawah'),
            'umur2' => Yii::t('app', 'Umur Batas Atas'),
            'Keterangan' => Yii::t('app', 'Keterangan'),
            'NoUrut' => Yii::t('app', 'No Urut'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
        ];
    }

}
