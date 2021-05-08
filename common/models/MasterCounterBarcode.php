<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "master_counter_barcode".
 *
 * @property integer $tahun
 * @property integer $value
 */
class MasterCounterBarcode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'master_counter_barcode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tahun', 'value'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tahun' => 'Tahun',
            'value' => 'Value',
        ];
    }
}
