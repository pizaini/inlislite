<?php

namespace common\models;

use Yii;
use \common\models\base\TujuanKunjungan as BaseTujuanKunjungan;

/**
 * This is the model class for table "tujuan_kunjungan".
 */
class TujuanKunjungan extends BaseTujuanKunjungan
{
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'TujuanKunjungan'], 'required'],
            [['Code'], 'required'], // Tambahan
            [['Member', 'NonMember', 'Rombongan', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Code', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['TujuanKunjungan'], 'string', 'max' => 255],
            [['Code'], 'unique'],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }
}
